@extends('layouts.app')

@section('title', 'Detail Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Aktivitas</h1>
        <a href="{{ route('activity_logs.index') }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- Left Column: Basic Info -->
        <div class="col-lg-4">
            <div class="card shadow mb-4 animate__animated animate__fadeInLeft">
                <div class="card-header py-3 bg-primary d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Informasi Dasar</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-secondary font-weight-bold">User (Pelaku)</label>
                        <div class="d-flex align-items-center mt-1">
                            @if($activity->causer)
                                <div class="mr-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark font-weight-bold">{{ $activity->causer->name }}</h6>
                                    <small class="text-muted">{{ $activity->causer->email }}</small>
                                </div>
                            @else
                                <span class="badge bg-secondary">Sistem / Tamu</span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>

                    <div class="mb-3">
                        <label class="small text-secondary font-weight-bold">Tipe Aksi</label>
                        <div class="mt-1">
                            @if($activity->event == 'created')
                                <span class="badge bg-success px-3 py-2">CREATED</span>
                            @elseif($activity->event == 'updated')
                                <span class="badge bg-warning text-dark px-3 py-2">UPDATED</span>
                            @elseif($activity->event == 'deleted')
                                <span class="badge bg-danger px-3 py-2">DELETED</span>
                            @else
                                <span class="badge bg-info px-3 py-2">{{ strtoupper($activity->event) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="small text-secondary font-weight-bold">Waktu Kejadian</label>
                        <p class="mb-0 text-dark font-weight-bold">
                            {{ $activity->created_at->format('d F Y') }}
                        </p>
                        <small class="text-muted">{{ $activity->created_at->format('H:i:s') }}</small>
                    </div>
                    
                    <div class="mb-0">
                         <label class="small text-secondary font-weight-bold">Deskripsi</label>
                         <p class="mb-0 text-dark">{{ $activity->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Detailed Changes -->
        <div class="col-lg-8">
            <div class="card shadow mb-4 animate__animated animate__fadeInRight">
                <div class="card-header py-3 bg-success d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-white">Detail Perubahan Data</h6>
                </div>
                <div class="card-body">
                    @if($activity->properties->count() > 0)
                        
                        {{-- Check if we have 'attributes' (new data) and 'old' (old data) keys --}}
                        @if(isset($activity->properties['attributes']) || isset($activity->properties['old']))
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Kolom</th>
                                            <th style="width: 35%" class="text-danger">Data Lama</th>
                                            <th style="width: 35%" class="text-success">Data Baru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Gabungkan semua keys dari attributes dan old --}}
                                        @php
                                            $new = $activity->properties['attributes'] ?? [];
                                            $old = $activity->properties['old'] ?? [];
                                            $keys = array_unique(array_merge(array_keys($new), array_keys($old)));
                                        @endphp

                                        @foreach($keys as $key)
                                            <tr>
                                                <td class="font-weight-bold">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                                <td class="text-danger">
                                                    @if(isset($old[$key]))
                                                        {{ is_array($old[$key]) ? json_encode($old[$key]) : $old[$key] }}
                                                    @else
                                                        <em class="text-muted">-</em>
                                                    @endif
                                                </td>
                                                <td class="text-success font-weight-bold">
                                                    @if(isset($new[$key]))
                                                        {{ is_array($new[$key]) ? json_encode($new[$key]) : $new[$key] }}
                                                    @else
                                                        <em class="text-muted">-</em>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- Fallback if structure is different (e.g. just attributes) --}}
                            <div class="bg-light p-3 rounded border">
                                <pre class="mb-0 text-dark" style="font-size: 0.9rem;">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif

                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-info-circle fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Tidak ada data properti spesifik yang tercatat untuk aktivitas ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection