@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Member: {{ $member->first_name }} {{ $member->last_name }}</h5>
                    <a href="{{ route('member.index') }}" class="btn btn-sm btn-outline-primary btn-custom">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Menampilkan Error Validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <strong>Oops! Terjadi kesalahan:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close btn-custom" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Formulir 'multipart/form-data' untuk upload gambar -->
                    <!-- Arahkan ke route 'member.update' dengan method PUT -->
                    <form action="{{ route('member.update', $member->id) }}" method="post" id="form-member-edit" enctype="multipart/form-data">
                        @csrf 
                        @method('PUT') {{-- PENTING: Tentukan method sebagai PUT untuk update --}}

                        <div class="row">
                            <!-- Kolom Kiri: Info Personal -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Nama Depan</label>
                                    <!-- Tampilkan data lama atau data dari $member -->
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}" placeholder="John" required>
                                    @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}" placeholder="Doe" required>
                                    @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $member->email) }}" placeholder="john.doe@example.com" required>
                                    @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kolom Kanan: Info Kontak & Foto -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $member->phone) }}" placeholder="08123456789">
                                    @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $member->tgl_lahir) }}">
                                    @error('tgl_lahir')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="imageProfile" class="form-label">Ganti Foto Profil</label>
                                    <input type="file" class="form-control" id="imageProfile" name="imageProfile">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                                    @error('imageProfile')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <!-- Tampilkan foto saat ini -->
                                    @if($member->imageProfile)
                                        <div class="mt-2">
                                            <img src="{{ asset('profiles/' . $member->imageProfile) }}" alt="Foto Profil Saat Ini" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                                            <small class="ms-2">Foto saat ini</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Baris Penuh: Alamat -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Jl. Pahlawan No. 10">{{ old('address', $member->address) }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-custom">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS (Sama seperti create) -->
<style>
    .btn-custom {
        background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%);
        border: none;
        color: white;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .btn-custom:hover {
        background: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
    }
    .btn-outline-primary.btn-custom {
        background: transparent;
        border-color: #5b86e5;
        color: #5b86e5;
    }
    .btn-outline-primary.btn-custom:hover {
        background: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
        color: white;
        border-color: #5b86e5;
    }
    .btn-close.btn-custom {
        padding: 0;
        border: none;
        background: none;
    }
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }
    .card-title {
        color: #333;
    }
    .form-control {
        border-radius: 10px;
        border-color: #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus {
        border-color: #36d1dc;
        box-shadow: 0 0 10px rgba(54, 209, 220, 0.2);
    }
    .animate__animated {
        animation-duration: 0.5s;
    }
    /* Animasi dari contoh Anda */
    .animate__fadeInUp { animation-name: fadeInUp; }
    .animate__fadeInDown { animation-name: fadeInDown; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translate3d(0, 50%, 0); }
        to { opacity: 1; transform: none; }
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translate3d(0, -50%, 0); }
        to { opacity: 1; transform: none; }
    }
</style>
@endsection
