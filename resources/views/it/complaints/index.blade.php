@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')

<div class="page-header">
    <div>
        <h4>Daftar Pengaduan</h4>
        <p>Semua pengaduan dari pelapor</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('it.complaints.index') }}"
               class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua
            </a>
            <a href="{{ route('it.complaints.index', ['status' => 'baru']) }}"
               class="btn btn-sm {{ $status === 'baru' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Baru
            </a>
            <a href="{{ route('it.complaints.index', ['status' => 'diproses']) }}"
               class="btn btn-sm {{ $status === 'diproses' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Diproses
            </a>
            <a href="{{ route('it.complaints.index', ['status' => 'menunggu_konfirmasi']) }}"
               class="btn btn-sm {{ $status === 'menunggu_konfirmasi' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Menunggu
            </a>
            <a href="{{ route('it.complaints.index', ['status' => 'selesai']) }}"
               class="btn btn-sm {{ $status === 'selesai' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Selesai
            </a>
            <a href="{{ route('it.complaints.index', ['status' => 'ditolak']) }}"
               class="btn btn-sm {{ $status === 'ditolak' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Ditolak
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($complaints->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h6>Tidak Ada Pengaduan</h6>
                <p>Belum ada pengaduan dengan filter ini</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th class="d-none d-md-table-cell">Pelapor</th>
                            <th class="d-none d-md-table-cell">PC</th>
                            <th class="d-none d-lg-table-cell">Kategori</th>
                            <th>Status</th>
                            <th class="d-none d-xl-table-cell">Teknisi</th>
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
                                        <span class="badge bg-danger mt-1">
                                            <i class="bi bi-arrow-repeat"></i> Berulang
                                        </span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div>{{ $c->user->name ?? '-' }}</div>
                                    <div class="text-muted small">{{ $c->user->department ?? '' }}</div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="fw-semibold">{{ $c->pc->kode_pc ?? '-' }}</div>
                                    <div class="text-muted small">{{ $c->pc->nama_pc ?? '' }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">{{ $c->category->nama_kategori ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $c->status_badge }}">
                                        {{ $c->status_label }}
                                    </span>
                                </td>
                                <td class="d-none d-xl-table-cell">{{ $c->teknisi->name ?? '-' }}</td>
                                <td class="text-muted small d-none d-lg-table-cell">
                                    {{ $c->created_at->format('d M Y') }}
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

            <div class="p-3">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>

@endsection