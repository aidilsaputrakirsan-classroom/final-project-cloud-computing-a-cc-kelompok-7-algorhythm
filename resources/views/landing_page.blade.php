@extends('layouts.landing')

@section('content')
<section class="hero-section position-relative overflow-hidden" style="background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); padding: 80px 0 100px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-white">
                                <h1 class="display-4 fw-bold mb-4">Jelajahi Ilmu Tanpa Batas di <span class="text-warning">Perpustakaan Digital</span></h1>
                <p class="lead mb-5 text-white-50">Temukan ribuan koleksi buku, jurnal, dan artikel ilmiah untuk menunjang kebutuhan akademik dan profesional Anda dengan mudah dan cepat.</p>
                
                <div class="bg-white p-2 rounded-pill shadow-lg" style="max-width: 500px;">
                    <form action="{{ route('landing') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control border-0 rounded-pill ps-4" placeholder="Cari Judul Buku atau Penulis..." value="{{ request('search') }}">
                        <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">Cari</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="{{ asset('assets/images/backgrounds/rocket.png') }}" alt="Hero Image" class="img-fluid floating-animation" style="max-width: 80%;">
            </div>
        </div>
    </div>
    
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 60C240 100 480 120 720 60C960 0 1200 20 1440 60V120H0V60Z" fill="#f8f9fa"/>
        </svg>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container mt-n5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <h4 class="fw-bold text-dark">{{ \App\Models\Book::count() }}</h4>
                                <p class="text-muted mb-0">Koleksi Buku</p>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0 border-start border-end">
                                <h4 class="fw-bold text-dark">{{ \App\Models\User::where('role', 'user')->count() }}</h4>
                                <p class="text-muted mb-0">Anggota Aktif</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="fw-bold text-dark">24/7</h4>
                                <p class="text-muted mb-0">Akses Digital</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light" id="katalog">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="text-primary fw-bold text-uppercase ls-1">Koleksi Kami</span>
                <h2 class="fw-bold mt-2">Buku Terbaru</h2>
            </div>
        </div>

        @if($books->isEmpty())
            <div class="alert alert-info text-center p-5 rounded-4">
                <h5>Belum ada buku yang tersedia atau ditemukan.</h5>
                <p>Silakan coba kata kunci lain atau kembali lagi nanti.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($books as $book)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm hover-top rounded-4 overflow-hidden">
                        <div class="position-relative overflow-hidden group">
                            <div style="height: 280px; background: #f8f9fa;" class="d-flex align-items-center justify-content-center overflow-hidden">
                                @if($book->book_cover && file_exists(public_path($book->book_cover)))
                                    <img src="{{ asset($book->book_cover) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $book->title }}">
                                @else
                                    <div class="text-center text-muted">
                                        <i class="ti ti-book-2 fs-1"></i>
                                        <p class="small mt-2 mb-0">No Cover</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-img-overlay d-flex flex-column justify-content-center align-items-center gap-2 p-3 opacity-0 group-hover-opacity transition-all" style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(2px);">
                                
                                <a href="{{ route('books.public.detail', $book->id) }}" class="btn btn-light text-primary fw-bold rounded-pill shadow-sm w-75">
                                    Detail Buku
                                </a>

                                @auth
                                    <form action="{{ route('bookmark.toggle', $book->id) }}" method="POST" class="w-75">
                                        @csrf
                                        <button type="submit" class="btn {{ Auth::user()->bookmarks->contains($book->id) ? 'btn-danger' : 'btn-outline-light' }} fw-bold rounded-pill shadow-sm w-100">
                                            <i class="ti ti-heart {{ Auth::user()->bookmarks->contains($book->id) ? 'text-white' : '' }}"></i> 
                                            {{ Auth::user()->bookmarks->contains($book->id) ? 'Disimpan' : 'Simpan' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-light fw-bold rounded-pill shadow-sm w-75">
                                        <i class="ti ti-heart"></i> Simpan
                                    </a>
                                @endauth

                            </div>
                        </div>

                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-dark mb-1 text-truncate" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h6>
                            <p class="card-text text-muted small mb-2">
                                <i class="ti ti-edit me-1"></i> {{ $book->author }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top">
                                <div class="small text-muted">
                                    Stok: <span class="fw-bold text-dark">{{ $book->bookStock->jmlh_tersedia ?? 0 }}</span>
                                </div>
                                <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-2 shadow-sm">
    {{ $book->category->name ?? 'Umum' }}
</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</section>

<style>
    .hover-top { transition: all 0.3s ease; }
    .hover-top:hover { transform: translateY(-10px); }
    
    .group:hover .group-hover-opacity { opacity: 1 !important; }
    .transition-all { transition: all 0.3s ease; }
    
    .floating-animation {
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }
</style>
@endsection