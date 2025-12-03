@extends('layouts.landing')

@section('content')
<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">📚 Koleksi Saya</h2>
                <p class="text-muted">Daftar buku yang telah Anda simpan.</p>
            </div>
            <a href="{{ route('landing') }}" class="btn btn-outline-primary rounded-pill">
                <i class="ti ti-plus me-1"></i> Cari Buku Lain
            </a>
        </div>

        @if($books->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="ti ti-bookmarks-off fs-1 text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted">Belum ada buku yang disimpan.</h4>
                <p class="text-muted mb-4">Yuk, cari buku menarik dan simpan di sini!</p>
                <a href="{{ route('landing') }}" class="btn btn-primary rounded-pill px-4">Jelajahi Katalog</a>
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
                                <a href="{{ route('books.public.detail', $book->id) }}" class="btn btn-light text-primary fw-bold rounded-pill shadow-sm w-75">Detail</a>
                                
                                <form action="{{ route('bookmark.toggle', $book->id) }}" method="POST" class="w-75">
                                    @csrf
                                    <button type="submit" class="btn btn-danger fw-bold rounded-pill shadow-sm w-100">
                                        <i class="ti ti-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold text-dark mb-1 text-truncate">{{ $book->title }}</h6>
                            <p class="card-text text-muted small mb-2">{{ $book->author }}</p>
                            <span class="badge bg-primary text-white px-3 py-2 rounded-pill mb-2 shadow-sm">
    {{ $book->category->name ?? 'Umum' }}
</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</section>

<style>
    .hover-top { transition: all 0.3s ease; }
    .hover-top:hover { transform: translateY(-5px); }
    .group:hover .group-hover-opacity { opacity: 1 !important; }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection