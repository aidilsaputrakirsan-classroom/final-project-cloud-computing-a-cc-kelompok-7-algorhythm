@extends('layouts.app')

@section('title', 'Edit Rak')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Rak: {{ $rack->name }}</h5>
                    {{-- PERBAIKAN DISINI: Mengubah route('racks.index') menjadi route('Rak.showdata') --}}
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

                    <form action="{{ route('racks.update', $rack->id) }}" method="post" id="form-rack-edit">
                        @csrf 
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Rak</label>
                                    <input type="text" class="form-control" id="name" name="rack_name" value="{{ old('rack_name', $rack->name) }}" placeholder="Contoh: Rak A1" required>
                                    @error('rack_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rak" class="form-label">Lantai</label>
                                    <input type="text" class="form-control" id="rak" name="rak" value="{{ old('rak', $rack->rak) }}" placeholder="Contoh: Lantai 2" required>
                                    @error('rak')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
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