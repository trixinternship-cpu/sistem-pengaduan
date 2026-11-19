@extends('layouts.app')

@section('title', 'Riwayat PC')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Riwayat PC: {{ $pc->kode_pc }}</h4>
        <p class="text-muted mb-0">
            {{ $pc->nama_pc }} — {{ $pc->departemen }} — {{ $pc->lokasi }}
        </p>
    </div>
    <a href="{{ route('it.pcs.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="text-muted small">Status</div>
                @php
                    $badge = match($pc->status) {
                        'aktif' => 'success',
                        'rusak' => 'danger',
                        'maintenance' => 'warning',
                        default => 'secondary',
                    };
                @endphp
                <span class="badge bg-{{ $badge }}">{{ ucfirst($pc->status) }}</span>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Pengguna</div>
                <div>{{ $pc->user->name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Total Kendala</div>
                <div class="fw-semibold">{{ $complaints->total() }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small">Nomor Aset</div>
                <div>{{ $pc->nomor_aset ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white"><strong>Daftar Riwayat Kendala</strong></div>
    <div class="card-body p-0">
        @if ($complaints->isEmpty())
            <div class="text-center text-muted py-5">
                Belum ada riwayat kendala untuk PC ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nomor</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Kendala</th>
                            <th>Status</th>
                            <th>Solusi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($complaints as $c)
                            <tr>
                                <td class="text-muted small">{{ $c->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $c->nomor_pengaduan }}</span>
                                    @if ($c->is_recurring)
                                        <span class="badge bg-danger" title="Kendala berulang">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $c->user->name ?? '-' }}</td>
                                <td>{{ $c->category->nama_kategori ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($c->what, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $c->status_badge }}">
                                        {{ $c->status_label }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ \Illuminate\Support\Str::limit($c->handling->solusi ?? '-', 50) }}
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
            <div class="p-3">{{ $complaints->links() }}</div>
        @endif
    </div>
</div>

@endsection