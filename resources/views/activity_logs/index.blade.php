@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-primary"><i class="ti ti-history me-2"></i>Log Aktivitas</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Timestamp</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ $log->created_at->format('d M Y H:i:s') }}
                                <br>
                                <span class="text-xs">{{ $log->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">{{ $log->user->name ?? 'System/Deleted' }}</h6>
                                        <small class="text-muted">{{ $log->user->role ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $badgeColor = match($log->action) {
                                        'CREATE' => 'success',
                                        'UPDATE' => 'warning',
                                        'DELETE' => 'danger',
                                        'LOGIN'  => 'info',
                                        default  => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-light-{{ $badgeColor }} text-{{ $badgeColor }} rounded-pill">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="text-dark">{{ $log->description }}</td>
                            <td>
                                @if($log->details)
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#detailModal{{ $log->id }}">
                                        <i class="ti ti-eye"></i> Lihat
                                    </button>

                                    <div class="modal fade" id="detailModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Aktivitas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body bg-light">
                                                    <pre class="small text-muted mb-0" style="white-space: pre-wrap;">{{ $log->details }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="ti ti-file-off fs-1 d-block mb-2"></i>
                                Belum ada aktivitas tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection