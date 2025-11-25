@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    
    <div class="card shadow mb-4 animate__animated animate__fadeIn">
        <div class="card-header py-3 bg-primary d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">Riwayat Aktivitas Sistem</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 15%">User (Pelaku)</th>
                            <th style="width: 10%">Action</th>
                            <th style="width: 25%">Description</th>
                            <th style="width: 15%">Timestamp</th>
                            <th style="width: 10%" class="text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $log)
                        <tr>
                            <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                            <td>
                                @if($log->causer)
                                    <span class="fw-bold text-dark">{{ $log->causer->name }}</span><br>
                                    <small class="text-muted">{{ $log->causer->email }}</small>
                                @else
                                    <span class="badge bg-secondary">Sistem/Tamu</span>
                                @endif
                            </td>
                            <td>
                                @if($log->event == 'created')
                                    <span class="badge bg-success">Created</span>
                                @elseif($log->event == 'updated')
                                    <span class="badge bg-warning text-dark">Updated</span>
                                @elseif($log->event == 'deleted')
                                    <span class="badge bg-danger">Deleted</span>
                                @else
                                    <span class="badge bg-info">{{ $log->event }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $log->description }}
                                <br>
                                <small class="text-muted">Pada Log: {{ class_basename($log->subject_type) }} (ID: {{ $log->subject_id }})</small>
                            </td>
                            <td>
                                {{ $log->created_at->format('d M Y') }}<br>
                                <small>{{ $log->created_at->format('H:i:s') }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('activity_logs.show', $log->id) }}" class="btn btn-sm btn-primary btn-custom">
    <i class="ti ti-eye"></i> Lihat
</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Detail Perubahan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6><strong>Properti (Data yang berubah):</strong></h6>
                                        <div class="bg-light p-3 rounded border">
                                            @if($log->properties->count() > 0)
                                                <pre class="mb-0" style="font-size: 0.85rem;">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                <p class="text-muted mb-0">Tidak ada detail properti tambahan.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4">Belum ada aktivitas tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .btn-custom {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
        border: none;
    }
</style>
@endsection