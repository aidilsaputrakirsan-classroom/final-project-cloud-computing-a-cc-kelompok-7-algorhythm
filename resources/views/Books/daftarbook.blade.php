@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="pb-2">
        @if (session('msg'))
            <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                {{ session('msg') }}
                <button type="button" class="btn-close btn-custom" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Buku Perpustakaan</h5>
                    <a href="{{ route('books.create') }}" class="btn btn-sm btn-custom text-white">
                        <i class="ti ti-plus"></i> Tambah Buku
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table datatable table-hover table-striped align-middle">
                            <thead class="custom-thead">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Cover Buku</th>
                                    <th scope="col" class="text-center">Judul</th>
                                    <th scope="col" class="text-center">Kategori</th>
                                    <th scope="col" class="text-center">Rak</th>
                                    <th scope="col" class="text-center">Stok</th>
                                    <th scope="col" class="text-center" width="25%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @forelse ($books as $index => $book)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                @if($book->book_cover)
                                                    <img src="{{ asset($book->book_cover) }}" alt="{{ $book->title }}" class="book-img">
                                                @else
                                                    <span class="badge bg-secondary">No Cover</span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column ps-3">
                                                <span class="fw-bold text-dark">{{ $book->title }}</span>
                                                <small class="text-muted">{{ $book->year }} &bull; {{ $book->author }}</small>
                                            </div>
                                        </td>

                                        <td class="text-center">{{ optional($book->category)->name ?? '-' }}</td>
                                        <td class="text-center">{{ optional($book->rack)->name ?? '-' }}</td>
                                        
                                        <td class="text-center text-dark">
                                            {{ optional($book->bookStock)->jmlh_tersedia ?? '0' }}
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                
                                                <a href="{{ route('Books.showDetail', $book->id) }}" class="btn btn-custom btn-sm text-white" title="Detail">
                                                    <i class="ti ti-eye"></i> Detail
                                                </a>

                                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-custom btn-sm text-white" title="Edit">
                                                    <i class="ti ti-pencil"></i> Edit
                                                </a>

                                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-custom btn-sm text-white" onclick="return confirmDelete()" title="Delete">
                                                        <i class="ti ti-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data buku tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus buku ini? Tindakan ini tidak dapat dibatalkan.");
    }
</script>

<style>
    /* Warna Utama (Biru Gradasi) - Dipakai untuk Header & Semua Tombol */
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
    
    .book-img {
        width: 50px;
        height: 70px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .custom-thead th {
        font-weight: 600;
        color: #555;
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