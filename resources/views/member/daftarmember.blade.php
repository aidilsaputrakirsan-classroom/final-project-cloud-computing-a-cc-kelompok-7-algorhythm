@extends('layouts.app')

@section('title', 'Daftar Member')

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
                    <h5 class="card-title mb-0">Daftar Anggota Perpustakaan</h5>
                    <a href="{{ route('member.create') }}" class="btn btn-sm btn-custom text-white">
                        <i class="ti ti-plus"></i> Tambah Anggota
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table datatable table-hover table-striped align-middle">
                            <thead class="custom-thead">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Foto Profil</th>
                                    <th scope="col" class="text-center">Nama</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Telepon</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @forelse ($members as $index => $member)
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="text-center">{{ $loop->iteration }}</td> <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                @if ($member->imageProfile)
                                                    <img src="{{ asset('/profiles/' . $member->imageProfile) }}"
                                                        alt="{{ $member->first_name }}"
                                                        class="profile-img shadow-sm">
                                                @else
                                                    <img src="{{ asset('path/to/default-avatar.png') }}" alt="Default" class="profile-img shadow-sm">
                                                @endif
                                            </div>
                                        </td>

                                        <td class="text-center fw-bold text-dark">
                                            {{ $member->first_name }} {{ $member->last_name }}
                                        </td>
                                        
                                        <td class="text-center">{{ $member->email ?? '-' }}</td>
                                        <td class="text-center">{{ $member->phone ?? '-' }}</td>
                                        
                                        <td class="text-center">
                                            <span class="badge status-badge {{ $member->status == 'Baru' ? 'bg-new' : 'bg-other' }}">
                                                {{ $member->status }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                
                                                <a href="{{ route('member.edit', $member->id) }}" class="btn btn-custom btn-sm text-white" title="Edit">
                                                    <i class="ti ti-pencil"></i> Edit 
                                                </a>

                                                <form action="{{ route('member.destroy', $member->id) }}" method="POST" style="display: inline;">
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
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada anggota terdaftar</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $members->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm("Apakah Anda yakin ingin menghapus anggota ini? Tindakan ini tidak dapat dibatalkan.");
    }
</script>

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

    /* Style khusus Foto Profil Bulat */
    .profile-img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
    }

    /* Style Badge Status */
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        color: white;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
    }
    .bg-new {
        background: linear-gradient(45deg, #4caf50, #81c784);
    }
    .bg-other {
        background: linear-gradient(45deg, #ff9800, #ffb74d);
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