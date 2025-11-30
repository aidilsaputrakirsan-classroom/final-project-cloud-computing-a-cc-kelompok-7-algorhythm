@extends('layouts.landing')

@section('content')
<section class="py-5 bg-white">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('landing') }}" class="text-decoration-none text-muted">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="row gx-5">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    @if($book->book_cover)
                        <img src="{{ asset($book->book_cover) }}" class="img-fluid w-100" alt="{{ $book->title }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 400px;">
                            <i class="ti ti-book-2 fs-1"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
                <div class="ps-lg-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">
                        {{ $book->category->name_category ?? 'Umum' }}
                    </span>
                    
                    <h1 class="fw-bold text-dark display-6 mb-2">{{ $book->title }}</h1>
                    <p class="text-muted fs-5 mb-4">Penulis: <span class="fw-medium text-dark">{{ $book->author }}</span></p>

                    <div class="row mb-4 p-3 bg-light rounded-3 mx-0">
                        <div class="col-6 col-md-3 mb-3 mb-md-0 border-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Penerbit</small>
                            <span class="fw-bold text-dark">{{ $book->publisher }}</span>
                        </div>
                        <div class="col-6 col-md-3 mb-3 mb-md-0 border-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Tahun</small>
                            <span class="fw-bold text-dark">{{ $book->year }}</span>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">ISBN</small>
                            <span class="fw-bold text-dark">{{ $book->isbn }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Lokasi Rak</small>
                            <span class="fw-bold text-primary">{{ $book->rack->name ?? '-' }}</span>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Sinopsis</h5>
                    <p class="text-muted lh-lg mb-5">
                        {{ $book->description ?? 'Belum ada deskripsi untuk buku ini.' }}
                    </p>

                    <div class="d-flex gap-3">
                        @auth
                            <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                <i class="ti ti-bookmark me-2"></i> Pinjam Buku
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                                <i class="ti ti-login me-2"></i> Login untuk Meminjam
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection