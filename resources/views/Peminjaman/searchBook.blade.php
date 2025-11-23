@extends('layouts.app')

@section('title', 'Cari Buku')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-sm animate__animated animate__fadeInUp mb-4">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Cari Buku</h5>
                    <a href="{{ route('Peminjaman.search') }}" class="btn btn-sm btn-outline-primary btn-custom-outline">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                            <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <i class="ti ti-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (request()->has('search') && request()->filled('search'))
                        <div class="alert alert-info d-flex align-items-center mb-4 shadow-sm border-0" role="alert">
                            <div class="bg-white p-2 rounded-circle me-3 text-primary shadow-sm">
                                <i class="ti ti-user fs-4"></i>
                            </div>
                            <div>
                                <small class="text-uppercase fw-bold text-muted" style="font-size: 0.75rem;">Peminjam</small>
                                <h6 class="fw-bold text-dark mb-0">{{ $member->first_name }} {{ $member->last_name }}</h6>
                                <small>{{ $member->email }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="row justify-content-center mt-2">
                        <div class="col-md-10">
                            <form action="{{ route('search.book.page') }}" method="GET">
                                @csrf
                                <input type="hidden" name="member_id" value="{{ $memberId }}">
                                
                                <div class="mb-3 text-center">
                                    <label for="search" class="form-label fw-bold text-secondary">Cari Berdasarkan Judul, Penulis, atau ISBN</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ti ti-search"></i></span>
                                        <input type="text" class="form-control form-control-lg" id="search" name="search" 
                                            placeholder="Masukkan kata kunci..." value="{{ request('search') }}" required>
                                        <button type="submit" class="btn btn-custom px-4">Cari Buku</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if (request()->has('search') && request()->filled('search'))
                @if (!empty($books) && $books->count() > 0)
                    <div class="card shadow-sm animate__animated animate__fadeInUp">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title mb-0 fw-bold text-dark">
                                <i class="ti ti-list me-2 text-primary"></i>Hasil Pencarian
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead class="custom-thead bg-light">
                                        <tr>
                                            <th scope="col" class="text-center py-3">No</th>
                                            <th scope="col" class="text-center">Cover</th>
                                            <th scope="col">Judul Buku</th>
                                            <th scope="col" class="text-center">Kategori & Rak</th>
                                            <th scope="col" class="text-center">Stok</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        @foreach ($books as $book)
                                            <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $loop->iteration * 0.1 }}s;">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                
                                                <td class="text-center">
                                                    @if($book->book_cover)
                                                        <img src="{{ asset($book->book_cover) }}" alt="Cover" class="shadow-sm rounded border" style="width: 45px; height: 65px; object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-secondary">No Cover</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold text-dark">{{ $book->title }}</span>
                                                        <small class="text-muted">{{ $book->author }} | {{ $book->year }}</small>
                                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $book->publisher }}</small>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark bg-opacity-10 border border-info mb-1">{{ optional($book->category)->name }}</span>
                                                    <br>
                                                    <span class="badge bg-secondary text-dark bg-opacity-10 border border-secondary">{{ optional($book->rack)->name }}</span>
                                                </td>

                                                <td class="text-center fw-bold {{ $book->bookStock->jmlh_tersedia > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $book->bookStock->jmlh_tersedia }}
                                                </td>

                                                <td class="text-center">
                                                    <form action="{{ route('createPinjaman') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="member_id" value="{{ $memberId }}">
                                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                        
                                                        <button type="submit" class="btn btn-sm btn-success-custom text-white px-3" {{ $book->bookStock->jmlh_tersedia <= 0 ? 'disabled' : '' }}>
                                                            <i class="ti ti-check me-1"></i> Pilih
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning text-center mt-3 shadow-sm border-0 animate__animated animate__fadeInUp" role="alert">
                        <i class="ti ti-alert-circle me-2 fs-5"></i> <br> Buku yang Anda cari tidak ditemukan.
                    </div>
                @endif
            @endif

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

    /* Tombol Kembali (Outline) */
    .btn-custom-outline {
        background: transparent;
        border: 1px solid #5b86e5;
        color: #5b86e5;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-custom-outline:hover {
        background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%);
        color: white;
        border-color: transparent;
    }

    /* Tombol Pilih/Simpan (Hijau Gradasi) */
    .btn-success-custom {
        background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
        border: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    .btn-success-custom:hover {
        background: linear-gradient(90deg, #38ef7d 0%, #11998e 100%);
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(56, 239, 125, 0.3);
    }
    .btn-success-custom:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Card Style */
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    /* Input Form Style */
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

    /* Tabel Header */
    .custom-thead th {
        font-weight: 600;
        color: #555;
        background-color: #f8f9fa;
    }

    .animate__animated {
        animation-duration: 0.5s;
    }
</style>
@endsection