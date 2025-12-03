@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="pb-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close btn-custom" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm animate__animated animate__fadeInUp">
                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Peminjaman</h5>
                    <a href="{{ route('Peminjaman.search') }}" class="btn btn-sm btn-custom text-white">
                        <i class="ti ti-plus me-1"></i> Peminjaman Baru
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table datatable table-hover table-striped align-middle">
                            <thead class="custom-thead">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Resi</th>
                                    <th scope="col" class="text-center">Nama Member</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Judul Buku</th>
                                    <th scope="col" class="text-center">Tanggal Pinjam</th>
                                    <th scope="col" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @php $counter = 0 @endphp
                                @foreach ($peminjamans as $index => $peminjaman)
                                    @if (is_null($peminjaman->return_date))
                                        @php $counter++ @endphp
                                        <tr class="animate__animated animate__fadeIn" style="animation-delay: {{ $index * 0.1 }}s;">
                                            <td class="text-center">{{ $counter }}</td>
                                            
                                            <td class="text-center fw-bold text-primary">
                                                {{ $peminjaman->resi_pjmn }}
                                            </td>

                                            <td class="text-center fw-bold text-dark">
                                                {{ $peminjaman->member->first_name ?? 'Unknown' }} {{ $peminjaman->member->last_name ?? '' }}
                                            </td>

                                            <td class="text-center">
                                                {{ $peminjaman->member->email ?? 'Unknown' }}
                                            </td>

                                            <td>
                                                <div class="text-center">
                                                    {{ $peminjaman->book->title ?? 'Unknown' }}
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                {{ $peminjaman->created_at->format('d-m-Y') }}
                                            </td>

                                            <td class="text-center">
                                                @php
                                                    $createdDate = \Carbon\Carbon::parse($peminjaman->created_at);
                                                    $daysSinceLoan = \Carbon\Carbon::now()->diffInDays($createdDate);

                                                    if ($createdDate->isToday()) {
                                                        $status = 'New';
                                                        $badgeClass = 'bg-new'; // Hijau
                                                    } elseif ($daysSinceLoan < 7) {
                                                        $status = 'Normal';
                                                        $badgeClass = 'bg-normal'; // Biru
                                                    } else {
                                                        $status = 'Jatuh Tempo';
                                                        $badgeClass = 'bg-overdue'; // Merah
                                                    }
                                                @endphp
                                                <span class="badge status-badge {{ $badgeClass }} animate__animated animate__pulse">
                                                    {{ $status }}
                                                    @if($status == 'Jatuh Tempo') <i class="ti ti-alert-circle ms-1"></i> @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tombol Utama (Biru Gradasi) */
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

    /* Card Style */
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

    /* Header Table */
    .custom-thead th {
        font-weight: 600;
        color: #555;
    }

    /* Badge Styles */
    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        color: white;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Warna Badge sesuai Logika Peminjaman */
    .bg-new {
        background: linear-gradient(45deg, #4caf50, #81c784); /* Hijau */
    }
    .bg-normal {
        background: linear-gradient(45deg, #2196f3, #64b5f6); /* Biru */
    }
    .bg-overdue {
        background: linear-gradient(45deg, #f44336, #e57373); /* Merah */
    }

    /* Animasi */
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