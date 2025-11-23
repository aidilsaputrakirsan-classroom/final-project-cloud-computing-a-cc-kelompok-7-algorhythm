@extends('layouts.app')

@section('title', 'Cari Pengembalian')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card shadow-sm animate__animated animate__fadeInUp mb-4">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Form Pengembalian Buku</h5>
                    <a href="{{ route('pengembalian') }}" class="btn btn-sm btn-outline-primary btn-custom-outline">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @isset($peminjaman)
                        @if($peminjaman->isNotEmpty())
                            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                                <i class="ti ti-check-circle me-2"></i> Peminjaman aktif ditemukan.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    @endisset

                    <div class="row justify-content-center mt-2">
                        <div class="col-md-8">
                            <form id="searchForm" action="{{ route('pengembalian.cari') }}" method="GET">
                                <div class="mb-3 text-center">
                                    <label for="keyword" class="form-label fw-bold text-secondary">Masukkan Nomor Resi Peminjaman</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="ti ti-search"></i></span>
                                        <input type="text" class="form-control form-control-lg" id="keyword" name="keyword" 
                                               placeholder="Contoh: TRX-123456" value="{{ request('keyword') }}" required>
                                        <button type="submit" class="btn btn-custom px-4">Cari Data</button>
                                    </div>
                                    <div class="form-text">Masukkan nomor resi peminjaman yang valid untuk memproses pengembalian.</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @isset($peminjaman)
                @if($peminjaman->isNotEmpty())
                    <div class="card shadow-sm animate__animated animate__fadeInUp">
                        <div class="card-header bg-white py-3">
                            <h6 class="card-title mb-0 fw-bold text-dark">
                                <i class="ti ti-list me-2 text-primary"></i>Detail Peminjaman Aktif
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle mb-0">
                                    <thead class="custom-thead bg-light">
                                        <tr>
                                            <th scope="col" class="text-center py-3">No</th>
                                            <th scope="col" class="text-center">Cover</th>
                                            <th scope="col" class="text-center">Resi</th>
                                            <th scope="col" class="text-center">Peminjam</th>
                                            <th scope="col" class="text-center">Buku</th>
                                            <th scope="col" class="text-center">Tgl Pinjam</th>
                                            <th scope="col" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        @foreach ($peminjaman as $key => $item)
                                            <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $key * 0.1 }}s;">
                                                <td class="text-center">{{ $key + 1 }}</td>
                                                
                                                <td class="text-center">
                                                    @if ($item->book && $item->book->book_cover)
                                                        <img src="{{ asset($item->book->book_cover) }}" alt="Cover" class="shadow-sm rounded border" style="width: 40px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <span class="badge bg-secondary">No Cover</span>
                                                    @endif
                                                </td>

                                                <td class="text-center fw-bold text-primary">{{ $item->resi_pjmn }}</td>
                                                
                                                <td>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-bold text-dark">{{ $item->member->first_name }}</span>
                                                        <small class="text-muted">{{ $item->member->email }}</small>
                                                    </div>
                                                </td>

                                                <td class="text-center text-dark fw-semibold">{{ $item->book->title ?? 'Unknown' }}</td>
                                                
                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark bg-opacity-10 border border-info px-3">
                                                        {{ $item->created_at->format('d-m-Y') }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    <form action="{{ route('pengembalian.simpan', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success-custom text-white px-3" onclick="return confirm('Proses pengembalian buku ini?')">
                                                            <i class="ti ti-check me-1"></i> Kembalikan
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
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3 animate__animated animate__shakeX" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endisset

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

    /* Tombol Kembalikan (Hijau Gradasi) */
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

    /* Card Style */
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        /* Efek hover card dimatikan agar tidak terlalu goyang saat input form */
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1); 
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