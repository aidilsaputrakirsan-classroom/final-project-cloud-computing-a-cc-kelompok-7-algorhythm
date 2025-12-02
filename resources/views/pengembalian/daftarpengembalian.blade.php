@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

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
                    <h5 class="card-title mb-0">Data Pengembalian Buku</h5>
                    
                    <a href="{{ route('pengembalian.search') }}" class="btn btn-sm btn-custom text-white">
                        <i class="ti ti-plus"></i> Pengembalian Buku
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table datatable table-hover table-striped align-middle">
                            <thead class="custom-thead">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Resi</th>
                                    <th scope="col" class="text-center">Email Member</th>
                                    <th scope="col" class="text-center">Tgl Pinjam</th>
                                    <th scope="col" class="text-center">Tgl Kembali</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Telat</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                @foreach ($pengembalians as $key => $pengembalian)
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        
                                        <td class="text-center fw-bold text-primary">
                                            {{ $pengembalian->resi_pjmn ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $pengembalian->member->email ?? 'Unknown' }}
                                        </td>

                                        <td class="text-center">
                                            {{ isset($pengembalian->created_at) ? \Carbon\Carbon::parse($pengembalian->created_at)->format('d-m-Y') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            {{ isset($pengembalian->return_date) ? \Carbon\Carbon::parse($pengembalian->return_date)->format('d-m-Y') : '-' }}
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $returnDate = \Carbon\Carbon::parse($pengembalian->return_date);
                                                $isToday = $returnDate->isToday();
                                                $statusText = $isToday ? 'Baru' : 'Selesai';
                                                $badgeClass = $isToday ? 'bg-new' : 'bg-secondary';
                                            @endphp
                                            <span class="badge status-badge {{ $badgeClass }} animate__animated animate__pulse">
                                                {{ $statusText }} 
                                                @if($isToday) <i class="ti ti-check ms-1"></i> @endif
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $tglPinjam = \Carbon\Carbon::parse($pengembalian->created_at);
                                                $tglKembali = \Carbon\Carbon::parse($pengembalian->return_date);
                                                $batasPinjam = $tglPinjam->copy()->addDays(7); // Pakai copy() agar aman
                                                
                                                $telat = 0;
                                                if ($tglKembali->greaterThan($batasPinjam)) {
                                                    $telat = $tglKembali->diffInDays($batasPinjam);
                                                }
                                            @endphp
                                            
                                            @if($telat > 0)
                                                <span class="text-danger fw-bold">{{ $telat }} Hari</span>
                                            @else
                                                <span class="text-success fw-bold">-</span>
                                            @endif
                                        </td>
                                    </tr>
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

    /* Status Badge */
    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        color: white;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .bg-new {
        background: linear-gradient(45deg, #4caf50, #81c784); /* Hijau */
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