<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\ActivityLog; // <--- PENTING: Import Model

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        
        $members = $query->latest()->paginate(10);
        $members->getCollection()->transform(function ($member) {
            $member->status = $member->created_at->isToday() ? 'Baru' : 'Lama';
            return $member;
        });

        return view('member.daftarmember', compact('members'));
    }

    public function create()
    {
        return view('member.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email|unique:tbl_members,email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'imageProfile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make('password123'),
            ]);

            $imageName = null;
            if ($request->hasFile('imageProfile')) {
                $image = $request->file('imageProfile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profiles'), $imageName);
            }

            $member = Member::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'tgl_lahir' => $request->tgl_lahir,
                'imageProfile' => $imageName,
            ]);

            DB::commit();

            // --- REKAM LOG ---
            ActivityLog::record(
                'CREATE',
                'Mendaftarkan Anggota Baru: ' . $member->first_name . ' ' . $member->last_name,
                ['email' => $member->email]
            );
            // -----------------

            return redirect()->route('member.index')->with('msg', 'Anggota baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($imageName) && File::exists(public_path('profiles/' . $imageName))) {
                File::delete(public_path('profiles/' . $imageName));
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menambahkan anggota. ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('member.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user; 

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . '|unique:tbl_members,email,' . $member->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'tgl_lahir' => 'nullable|date',
            'imageProfile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if($user) {
                $user->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                ]);
            }

            $imageName = $member->imageProfile;
            if ($request->hasFile('imageProfile')) {
                if ($imageName && File::exists(public_path('profiles/' . $imageName))) {
                    File::delete(public_path('profiles/' . $imageName));
                }
                $image = $request->file('imageProfile');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profiles'), $imageName);
            }

            $member->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'tgl_lahir' => $request->tgl_lahir,
                'imageProfile' => $imageName,
            ]);

            DB::commit();

            // --- REKAM LOG ---
            ActivityLog::record(
                'UPDATE',
                'Memperbarui Data Anggota: ' . $member->first_name . ' ' . $member->last_name
            );
            // -----------------

            return redirect()->route('member.index')->with('msg', 'Data anggota berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal memperbarui data anggota. ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $namaMember = $member->first_name . ' ' . $member->last_name; // Simpan nama

        if ($member->imageProfile) {
            $imageProfilePath = public_path('profiles/' . $member->imageProfile);
            if (File::exists($imageProfilePath)) {
                File::delete($imageProfilePath);
            }
        }
        
        if ($member->qr_code) {
            $qrCodePath = public_path('qrcodes/' . $member->qr_code);
            if (File::exists($qrCodePath)) {
                File::delete($qrCodePath);
            }
        }

        $user = User::find($member->user_id);
        if ($user) {
            $user->delete();
        }
        $member->delete();

        // --- REKAM LOG ---
        ActivityLog::record(
            'DELETE',
            'Menghapus Anggota: ' . $namaMember
        );
        // -----------------

        return redirect()->route('member.index')->with('msg', 'Anggota dan pengguna terkait berhasil dihapus.');
    }
}