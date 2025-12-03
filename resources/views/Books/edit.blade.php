@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Buku: {{ $book->title }}</h5>
                    <a href="{{ route('member.index') }}" class="btn btn-sm btn-outline-custom">
    <i class="ti ti-arrow-left"></i> Kembali
</a>
                </div>

                <div class="card-body">
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

                    <form action="{{ route('books.update', $book->id) }}" method="post" id="form-book-edit" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Buku</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $book->title) }}" required>
                                    @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="author" class="form-label">Penulis</label>
                                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $book->author) }}" required>
                                    @error('author') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="publisher" class="form-label">Penerbit</label>
                                    <input type="text" class="form-control" id="publisher" name="publisher" value="{{ old('publisher', $book->publisher) }}">
                                    @error('publisher') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="year" class="form-label">Tahun Terbit</label>
                                        <input type="number" class="form-control" id="year" name="year" value="{{ old('year', $book->year) }}">
                                        @error('year') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}">
                                        @error('isbn') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Kategori</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="rack_id" class="form-label">Lokasi Rak</label>
                                        <select class="form-control" id="rack_id" name="rack_id">
                                            @foreach ($racks as $rack)
                                                <option value="{{ $rack->id }}" {{ old('rack_id', $book->rack_id) == $rack->id ? 'selected' : '' }}>
                                                    {{ $rack->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('rack_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah" class="form-label">Stok Buku</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', optional($book->bookStock)->jmlh_tersedia ?? 0) }}">
                                        @error('jumlah') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="book_cover" class="form-label">Update Cover Buku</label>
                                    <input type="file" class="form-control" id="book_cover" name="book_cover">
                                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti cover.</small>
                                    @error('book_cover') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                                    @if($book->book_cover)
                                        <div class="mt-2">
                                            <img src="{{ asset($book->book_cover) }}" alt="Current Cover" style="height: 100px; width: auto; object-fit: cover; border-radius: 5px; border: 1px solid #dee2e6;">
                                            <small class="ms-2 text-muted">Cover saat ini</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $book->description) }}</textarea>
                                    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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