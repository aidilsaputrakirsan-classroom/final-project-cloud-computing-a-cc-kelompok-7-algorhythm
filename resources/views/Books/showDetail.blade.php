@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detail Buku: {{ $book->title }}</h5>
                   <a href="{{ route('member.index') }}" class="btn btn-sm btn-outline-custom">
    <i class="ti ti-arrow-left"></i> Kembali
</a>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Judul Buku</label>
                                <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                    {{ $book->title }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Penulis</label>
                                <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                    {{ $book->author }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Penerbit</label>
                                <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                    {{ $book->publisher }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tahun</label>
                                    <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                        {{ $book->year }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">ISBN</label>
                                    <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                        {{ $book->isbn }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                    {{ optional($book->category)->name ?? '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Lokasi Rak</label>
                                    <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                        {{ optional($book->rack)->name ?? '-' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Stok</label>
                                    <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa;">
                                        {{ optional($book->bookStock)->jmlh_tersedia ?? 0 }} Pcs
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Cover Buku</label>
                                <div class="mt-1 text-center">
                                    @if($book->book_cover)
                                        <img src="{{ asset($book->book_cover) }}" alt="{{ $book->title }}" class="img-fluid shadow-sm" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; border: 1px solid #dee2e6;">
                                    @else
                                        <div class="border rounded d-flex align-items-center justify-content-center text-muted mx-auto" style="height: 250px; width: 100%; background-color: #f8f9fa;">
                                            No Cover
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <div class="form-control-plaintext border rounded px-3 py-2" style="background-color: #f8f9fa; min-height: 80px; text-align: justify;">
                                    {{ $book->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-custom">
                            <i class="ti ti-pencil"></i> Edit Buku Ini
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ini adalah Potongan CSS untuk Tombol Kembali */
.btn-outline-custom {
    background: transparent;       /* Transparan saat diam */
    border: 1px solid #5b86e5;     /* Garis biru */
    color: #5b86e5;                /* Teks biru */
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-outline-custom:hover {
    background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%); /* Gradient saat hover */
    color: white;                  /* Teks jadi putih */
    border-color: transparent;     /* Garis hilang */
    transform: translateY(-2px);   /* Efek naik sedikit */
}
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
    .form-control-plaintext {
        border-radius: 10px !important;
        border-color: #ced4da !important;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .animate__animated {
        animation-duration: 0.5s;
    }
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