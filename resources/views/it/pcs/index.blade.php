@extends('layouts.app')

@section('title', 'Data Perangkat')

@section('content')

<div class="page-header">
    <div>
        <h4>Data Perangkat</h4>
        <p>Master data komputer perusahaan</p>
    </div>
    <a href="{{ route('it.pcs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Perangkat
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($pcs->isEmpty())
            <div class="empty-state">
                <i class="bi bi-pc-display empty-icon"></i>
                <h6>Belum Ada Perangkat</h6>
                <p>Tambahkan perangkat untuk mulai mengelola</p>
                <a href="{{ route('it.pcs.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Perangkat
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th class="d-none d-md-table-cell">Nama</th>
                            <th class="d-none d-lg-table-cell">Departemen</th>
                            <th class="d-none d-lg-table-cell">Lokasi</th>
                            <th class="d-none d-xl-table-cell">Pengguna</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Kendala</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pcs as $pc)
                            <tr>
                                <td class="fw-semibold">{{ $pc->kode_pc }}</td>
                                <td class="d-none d-md-table-cell">{{ $pc->nama_pc }}</td>
                                <td class="d-none d-lg-table-cell">{{ $pc->departemen }}</td>
                                <td class="d-none d-lg-table-cell">{{ $pc->lokasi }}</td>
                                <td class="d-none d-xl-table-cell">{{ $pc->user->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($pc->status) {
                                            'aktif' => 'success',
                                            'rusak' => 'danger',
                                            'maintenance' => 'warning',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($pc->status) }}</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <a href="{{ route('it.pcs.history', $pc->id) }}"
                                       class="badge bg-primary text-decoration-none">
                                        {{ $pc->complaints_count }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('it.pcs.edit', $pc->id) }}"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('it.pcs.destroy', $pc->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus {{ $pc->kode_pc }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $pcs->links() }}</div>
        @endif
    </div>
</div>

@endsection