@extends('layouts.app')

@section('title', 'Daftar Rak Buku')

@section('content')
    <div class="container mt-4 animate__animated animate__fadeIn">
        <div class="pb-2">
            @if (session('msg'))
                <div class="alert {{ session('error') ? 'alert-danger' : 'alert-success' }} alert-dismissible fade show animate__animated animate__fadeInDown"
                    role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close btn-custom" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif (session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-custom" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm animate__animated animate__fadeInUp">
                    
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Data Rak Buku</h5>
                        <a href="{{ route('Rak.createRak') }}" class="btn btn-sm btn-custom text-white animate__animated animate__pulse">
                            <i class="ti ti-plus"></i> Tambah Rak
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable table-hover table-striped align-middle" id="rackTable">
                                <thead class="custom-thead">
                                    <tr>
                                        <th scope="col" class="text-center">No</th>
                                        <th scope="col" class="text-center">Nama Rak</th>
                                        <th scope="col" class="text-center">Lantai</th>
                                        <th scope="col" class="text-center">Jumlah Buku</th>
                                        <th scope="col" class="text-center" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @forelse ($racks as $index => $rack)
                                        <tr class="animate__animated animate__fadeIn">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center fw-bold text-dark">{{ $rack->name }}</td>
                                            <td class="text-center">{{ $rack->rak }}</td>
                                            
                                            <td class="text-center text-dark">
                                                {{ $rack->books_count }}
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('racks.edit', $rack->id) }}" class="btn btn-custom btn-sm text-white">
                                                        <i class="ti ti-pencil"></i> Edit
                                                    </a>

                                                    <form action="{{ route('racks.destroy', $rack->id) }}" method="post" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-custom btn-sm text-white" onclick="return confirm('Apakah Anda yakin ingin menghapus rak ini?')">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data rak tersedia</td>
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

    <style>
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