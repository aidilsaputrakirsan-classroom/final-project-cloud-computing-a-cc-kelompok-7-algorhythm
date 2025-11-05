<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar semua anggota.
     */
    public function index(Request $request)
    {
        // Logika pencarian sederhana
        $query = Member::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        
        // Ambil data paginator
        $members = $query->latest()->paginate(10);

        // Gunakan getCollection()->transform() untuk memodifikasi data
        // Ini tidak merusak Paginator sehingga ->links() tetap berfungsi
        $members->getCollection()->transform(function ($member) {
            // Cek status member baru (dibuat hari ini)
            $member->status = $member->created_at->isToday() ? 'Baru' : 'Lama';
            return $member; // Kembalikan member yang sudah dimodifikasi
        });

        // Mengirim data ke view 'member.daftarmember'
        return view('member.daftarmember', compact('members'));
    }

    /**
     * Menampilkan formulir untuk membuat anggota baru.
     */
    public function create()
    {
        // Mengarahkan ke view 'member.create'
        return view('member.create');
    }

    /**
     * Menyimpan anggota baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input disesuaikan dengan migrasi
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email|unique:tbl_members,email', // Cek unik di kedua tabel
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'imageProfile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'imageProfile.image' => 'File harus berupa gambar.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
        ]);

        // Gunakan DB Transaction untuk memastikan data (User & Member) konsisten
        DB::beginTransaction();

        try {
            // 1. Buat User baru (Untuk Login)
            $defaultPassword = 'password123'; // Ganti ini sesuai kebutuhan Anda
            
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                // 'role' => 'member', // Jika Anda memiliki field role
            ]);

            // 2. Handle Upload Foto Profil
            $imageName = null;
            if ($request->hasFile('imageProfile')) {
                $image = $request->file('imageProfile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profiles'), $imageName);
            }

            // 3. Buat Member baru (Untuk Data Profil)
            Member::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email, // Simpan email juga di tabel member
                'phone' => $request->phone,
                'address' => $request->address,
                'tgl_lahir' => $request->tgl_lahir,
                'imageProfile' => $imageName,
                // 'qr_code' akan null, diasumsikan di-generate di proses lain
            ]);

            // Jika semua berhasil, commit transaction
            DB::commit();

            return redirect()->route('member.index')->with('msg', 'Anggota baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Jika terjadi error, rollback semua perubahan
            DB::rollBack();

            // Hapus file gambar jika sudah terlanjur di-upload
            if (isset($imageName) && File::exists(public_path('profiles/' . $imageName))) {
                File::delete(public_path('profiles/' . $imageName));
            }

            // Laporkan error (untuk debugging) dan kirim pesan error ke user
            // Log::error('Gagal menyimpan member baru: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan anggota. Silakan coba lagi. ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan formulir untuk mengedit data anggota.
     */
    public function edit($id)
    {
        // Cari anggota berdasarkan ID, jika tidak ketemu akan 404
        $member = Member::findOrFail($id);
        
        // Mengarahkan ke view 'member.edit' dengan data anggota
        return view('member.edit', compact('member'));
    }

    /**
     * Memperbarui data anggota di database.
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        // Asumsi relasi 'user' sudah didefinisikan di model Member
        $user = $member->user; 

        // Validasi input
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            // Perhatikan: validasi unik di-update untuk mengabaikan ID user dan member saat ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . '|unique:tbl_members,email,' . $member->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'imageProfile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'first_name.required' => 'Nama depan wajib diisi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update data User
            if($user) { // Pastikan user ada
                $user->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                ]);
            }

            // 2. Handle Upload Foto Profil (jika ada yang baru)
            $imageName = $member->imageProfile; // Nama gambar lama
            if ($request->hasFile('imageProfile')) {
                // Hapus gambar lama jika ada
                if ($imageName && File::exists(public_path('profiles/' . $imageName))) {
                    File::delete(public_path('profiles/' . $imageName));
                }

                // Upload gambar baru
                $image = $request->file('imageProfile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profiles'), $imageName);
            }

            // 3. Update data Member
            $member->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'tgl_lahir' => $request->tgl_lahir,
                'imageProfile' => $imageName, // Simpan nama gambar baru (atau nama lama jika tidak diubah)
            ]);

            DB::commit();

            return redirect()->route('member.index')->with('msg', 'Data anggota berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Gagal update member: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui data anggota. ' . $e->getMessage()]);
        }
    }


    /**
     * Menghapus anggota dari database.
     */
    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // Hapus file imageProfile jika ada
        if ($member->imageProfile) {
            $imageProfilePath = public_path('profiles/' . $member->imageProfile);
            if (File::exists($imageProfilePath)) {
                File::delete($imageProfilePath);
            }
        }
        
        // Hapus file qr_code jika ada (sesuai migrasi Anda)
        if ($member->qr_code) { // Periksa field dari $member
            $qrCodePath = public_path('qrcodes/' . $member->qr_code);
            if (File::exists($qrCodePath)) {
                File::delete($qrCodePath);
            }
        }

        // Temukan pengguna yang terkait dengan anggota
        $user = User::find($member->user_id);

        // Hapus pengguna (akun login) jika ditemukan
        // Relasi cascade di migrasi Anda juga akan menangani ini,
        // tapi menghapus secara manual di PHP lebih eksplisit
        if ($user) {
            $user->delete();
        }

        // Hapus anggota (data profil)
        // Jika foreign key di-set 'onDelete(cascade)', 
        // menghapus user juga akan menghapus member
        // Tapi kita lakukan $member->delete() untuk jaga-jaga
        // atau jika $user-nya tidak ketemu
        $member->delete();

        return redirect()->route('member.index')->with('msg', 'Anggota dan pengguna terkait berhasil dihapus.');
    }
}

