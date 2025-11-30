@extends('layouts.landing')

@section('content')
<section class="py-5 bg-white">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('landing') }}" class="text-decoration-none text-muted fw-bold">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Katalog
            </a>
        </div>

        <div class="row gx-5">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    {{-- Pastikan path gambar sesuai dengan yang berhasil di landing page --}}
                    @if($book->book_cover && file_exists(public_path($book->book_cover)))
                        <img src="{{ asset($book->book_cover) }}" class="img-fluid w-100" alt="{{ $book->title }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 400px;">
                            <div class="text-center">
                                <i class="ti ti-book-2 fs-1"></i>
                                <p class="mt-2">Cover Tidak Tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
            <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-2 shadow-sm">
                {{ $book->category->name ?? 'Umum' }}
            </span>

                <h1 class="fw-bold text-dark display-6 mb-2">{{ $book->title }}</h1>
                <p class="text-muted fs-5 mb-4">Ditulis oleh: <span class="fw-bold text-dark">{{ $book->author }}</span></p>

                <div class="row mb-4 bg-light p-3 rounded-3 mx-0">
                    <div class="col-6 col-md-3 text-center border-end">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Penerbit</small>
                        <div class="fw-bold text-dark mt-1">{{ $book->publisher }}</div>
                    </div>
                    <div class="col-6 col-md-3 text-center border-end">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Tahun</small>
                        <div class="fw-bold text-dark mt-1">{{ $book->year }}</div>
                    </div>
                    <div class="col-6 col-md-3 text-center border-end">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Stok</small>
                        <div class="fw-bold text-success mt-1">{{ $book->bookStock->jmlh_tersedia ?? 0 }}</div>
                    </div>
                    <div class="col-6 col-md-3 text-center">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Rak</small>
                        <div class="fw-bold text-primary mt-1">{{ $book->rack->name ?? '-' }}</div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Sinopsis Buku</h5>
                <p class="text-muted lh-lg mb-5">
                    {{ $book->description ?? 'Belum ada deskripsi untuk buku ini.' }}
                </p>

                <div class="d-flex gap-2">
                    @auth
                        {{-- (Tambahkan form bookmark di sini nanti) --}}
                        <button class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="ti ti-bookmark me-2"></i> Simpan Buku
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="ti ti-login me-2"></i> Login untuk Menyimpan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection