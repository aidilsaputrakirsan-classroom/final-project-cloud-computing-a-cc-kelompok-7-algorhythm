@extends('layouts.app')

@section('title', 'Cari Member Peminjaman')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-sm animate__animated animate__fadeInUp mb-4">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cari Anggota Peminjam</h5>
                    <a href="{{ route('peminjaman') }}" class="btn btn-sm btn-outline-custom">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center mt-2">
                        <div class="col-md-8">
                            <div class="mb-3 text-center">
                                <label for="email" class="form-label fw-bold text-secondary">Masukkan Email Anggota</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ti ti-email"></i></span>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                           placeholder="Contoh: member@example.com">
                                    <button class="btn btn-custom px-4" onclick="searchMemberByEmail()">
                                        <i class="ti ti-search me-1"></i> Cari
                                    </button>
                                </div>
                                <div class="form-text">Pastikan email anggota sudah terdaftar di sistem.</div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm animate__animated animate__fadeInUp d-none" id="memberTableContainer">
                <div class="card-header bg-white py-3">
                    <h6 class="card-title mb-0 fw-bold text-dark">
                        <i class="ti ti-user me-2 text-primary"></i>Data Anggota Ditemukan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                            <thead class="custom-thead bg-light">
                                <tr>
                                    <th scope="col" class="text-center">Profil</th>
                                    <th scope="col">Nama Lengkap</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Telepon</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="memberTableBody" class="table-group-divider">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Tombol Utama (Biru Gradasi) */
    .btn-custom {
        background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%);
        border: none;
        color: white;
        font-weight: bold;
        transition: background 0.3s ease;
    }
    .btn-custom:hover {
        background: linear-gradient(90deg, #5b86e5 0%, #36d1dc 100%);
        color: white;
        transform: translateY(-2px);
    }

    /* Tombol Outline (Kembali) */
    .btn-outline-custom {
        background: transparent;
        border: 1px solid #5b86e5;
        color: #5b86e5;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-outline-custom:hover {
        background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%);
        color: white;
        border-color: transparent;
    }

    /* Tombol Pilih (Hijau Gradasi) - Akan disuntikkan via JS */
    .btn-success-custom {
        background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-success-custom:hover {
        background: linear-gradient(90deg, #38ef7d 0%, #11998e 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 239, 125, 0.3);
    }

    /* Card Style */
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    /* Input Form */
    .form-control-lg {
        border-radius: 0 10px 10px 0;
        border: 1px solid #ced4da;
        font-size: 1rem;
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1px solid #ced4da;
        background-color: #f8f9fa;
        color: #5b86e5;
    }
    .form-control:focus {
        border-color: #5b86e5;
        box-shadow: 0 0 0 0.2rem rgba(91, 134, 229, 0.25);
    }
    
    /* Foto Profil Bulat */
    .profile-image {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
    }

    /* Header Table */
    .custom-thead th {
        font-weight: 600;
        color: #555;
        background-color: #f8f9fa;
    }

    .animate__animated {
        animation-duration: 0.5s;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function searchMemberByEmail() {
        const email = $('#email').val();
        
        // Reset UI
        $('#memberTableBody').empty();
        $('#memberTableContainer').addClass('d-none');

        if(!email) {
            Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Harap masukkan email anggota!' });
            return;
        }

        $.ajax({
            url: "{{ route('search.member.by.email') }}",
            type: 'GET',
            data: { email: email },
            success: function(response) {
                if (response.member) {
                    // Validasi data lengkap (Opsional, sesuaikan kebutuhan)
                    if (!response.member.email || !response.member.phone) {
                        Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', text: 'Data anggota ini belum lengkap.' });
                        return;
                    }

                    // Tampilkan Data
                    $('#memberTableContainer').removeClass('d-none');
                    
                    let profileImg = response.member.imageProfile 
                        ? `{{ asset('/profiles') }}/${response.member.imageProfile}` 
                        : `{{ asset('path/to/default-avatar.png') }}`;

                    $('#memberTableBody').html(`
                        <tr class="animate__animated animate__fadeIn">
                            <td class="text-center">
                                <img src="${profileImg}" alt="Profile" class="profile-image shadow-sm">
                            </td>
                            <td class="fw-bold text-dark">${response.member.first_name} ${response.member.last_name}</td>
                            <td>${response.member.email}</td>
                            <td>${response.member.phone}</td>
                            <td>${response.member.address}</td>
                            <td class="text-center">
                                <form action="{{ route('search.book.page') }}" method="GET">
                                    <input type="hidden" name="member_id" value="${response.member.id}">
                                    <button type="submit" class="btn btn-sm btn-success-custom px-3">
                                        <i class="ti ti-check me-1"></i> Pilih
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `);

                    Swal.fire({ icon: 'success', title: 'Ditemukan!', text: 'Anggota berhasil ditemukan.', timer: 1500, showConfirmButton: false });

                } else {
                    Swal.fire({ icon: 'error', title: 'Tidak Ditemukan', text: 'Email anggota tidak terdaftar.' });
                }
            },
            error: function(xhr) {
                console.log(xhr);
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: 'Silakan coba lagi nanti.' });
            }
        });
    }
</script>
@endsection