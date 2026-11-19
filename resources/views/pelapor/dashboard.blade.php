@extends('layouts.app')

@section('title', 'Dashboard Pelapor')

@section('content')

<div class="page-header">
    <div>
        <h4>Dashboard</h4>
        <p>Ringkasan pengaduan Anda</p>
    </div>
    <a href="{{ route('pelapor.complaints.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Buat Pengaduan
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Laporan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card">
            <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                <i class="bi bi-hourglass"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['baru'] }}</div>
                <div class="stat-label">Baru</div>
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
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="stat-value">{{ $stats['selesai'] }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Pengaduan Terbaru</strong>
        <a href="{{ route('pelapor.complaints.index') }}" class="btn btn-sm btn-outline-secondary">
            Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if ($recent->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h6>Belum Ada Pengaduan</h6>
                <p>Anda belum membuat pengaduan apapun</p>
                <a href="{{ route('pelapor.complaints.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Buat Pengaduan Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>PC</th>
                            <th class="d-none d-md-table-cell">Kategori</th>
                            <th>Status</th>
                            <th class="d-none d-lg-table-cell">Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recent as $c)
                            <tr>
                                <td><span class="fw-semibold">{{ $c->nomor_pengaduan }}</span></td>
                                <td>{{ $c->pc->kode_pc ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ $c->category->nama_kategori ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $c->status_badge }}">
                                        {{ $c->status_label }}
                                    </span>
                                </td>
                                <td class="text-muted small d-none d-lg-table-cell">
                                    {{ $c->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('pelapor.complaints.show', $c->id) }}"
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

@endsection