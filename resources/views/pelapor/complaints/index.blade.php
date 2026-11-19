@extends('layouts.app')

@section('title', 'Riwayat Pengaduan')

@section('content')

<div class="page-header">
    <div>
        <h4>Riwayat Pengaduan</h4>
        <p>Semua pengaduan yang pernah Anda buat</p>
    </div>
    <a href="{{ route('pelapor.complaints.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Buat Pengaduan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($complaints->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h6>Belum Ada Pengaduan</h6>
                <p>Mulai laporkan kendala Anda</p>
                <a href="{{ route('pelapor.complaints.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Buat Pengaduan
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th class="d-none d-md-table-cell">PC</th>
                            <th class="d-none d-lg-table-cell">Kategori</th>
                            <th class="d-none d-lg-table-cell">Jenis</th>
                            <th>Status</th>
                            <th class="d-none d-lg-table-cell">Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaints as $c)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $c->nomor_pengaduan }}</div>
                                    @if ($c->is_recurring)
                                        <span class="badge bg-danger mt-1" title="Kendala berulang">
                                            <i class="bi bi-arrow-repeat"></i> Berulang
                                        </span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="fw-semibold">{{ $c->pc->kode_pc ?? '-' }}</div>
                                    <div class="text-muted small">{{ $c->pc->nama_pc ?? '' }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">{{ $c->category->nama_kategori ?? '-' }}</td>
                                <td class="d-none d-lg-table-cell">{{ $c->jenis_kendala }}</td>
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

            <div class="p-3">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>

@endsection