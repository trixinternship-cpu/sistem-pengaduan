@extends('layouts.app')

@section('title', 'Dashboard IT')

@section('content')

<div class="page-header">
    <div>
        <h4>Dashboard IT</h4>
        <p>Ringkasan pengaduan dan data master</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                <i class="bi bi-inbox"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['baru'] }}</div>
                <div class="stat-label">Pengaduan Baru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-tools"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['diproses'] }}</div>
                <div class="stat-label">Diproses</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['menunggu'] }}</div>
                <div class="stat-label">Menunggu Konfirmasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['selesai'] }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                <i class="bi bi-arrow-repeat"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['berulang'] }}</div>
                <div class="stat-label">Kendala Berulang</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-pc-display"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['total_pc'] }}</div>
                <div class="stat-label">Total Perangkat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-dark bg-opacity-10 text-dark">
                <i class="bi bi-people"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['total_user'] }}</div>
                <div class="stat-label">Total User</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Pengaduan Perlu Ditangani</strong>
                <a href="{{ route('it.complaints.index') }}" class="btn btn-sm btn-outline-secondary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if ($terbaru->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-check2-circle empty-icon" style="color: #10b981;"></i>
                        <h6>Semua Beres! 🎉</h6>
                        <p>Tidak ada pengaduan baru yang perlu ditangani</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th class="d-none d-md-table-cell">Perangkat</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($terbaru as $c)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $c->nomor_pengaduan }}</div>
                                            <div class="text-muted small">
                                                {{ $c->user->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div>{{ $c->pc->kode_pc ?? '-' }}</div>
                                            <div class="text-muted small">
                                                {{ $c->category->nama_kategori ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $c->status_badge }}">
                                                {{ $c->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('it.complaints.show', $c->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <strong>Perangkat dengan Kendala Terbanyak</strong>
            </div>
            <div class="card-body p-0">
                @if ($topPc->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-pc-display empty-icon"></i>
                        <h6>Belum Ada Data</h6>
                        <p>Belum ada perangkat yang tercatat</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($topPc as $pc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="min-w-0">
                                    <div class="fw-semibold">{{ $pc->kode_pc }}</div>
                                    <div class="text-muted small text-truncate">{{ $pc->nama_pc }}</div>
                                </div>
                                <span class="badge bg-primary rounded-pill flex-shrink-0">
                                    {{ $pc->complaints_count }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection