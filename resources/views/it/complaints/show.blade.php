@extends('layouts.app')

@section('title', 'Detail Pengaduan IT')

@section('content')

<div class="page-header">
    <div>
        <h4>{{ $complaint->nomor_pengaduan }}</h4>
        <p>Dibuat {{ $complaint->created_at->format('d M Y H:i') }} oleh <strong>{{ $complaint->user->name ?? '-' }}</strong></p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('chat.show', $complaint->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-chat-dots"></i> Chat
        </a>
        @if ($complaint->status !== 'selesai')
            <a href="{{ route('it.handling.create', $complaint->id) }}" class="btn btn-primary">
                <i class="bi bi-tools"></i> {{ $complaint->handling ? 'Edit Penanganan' : 'Tangani' }}
            </a>
        @endif
        <a href="{{ route('it.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if ($complaint->is_recurring)
    <div class="alert alert-danger">
        <i class="bi bi-arrow-repeat"></i>
        <strong>Kendala berulang.</strong>
        Pelapor melaporkan kendala yang mirip dengan riwayat sebelumnya.
    </div>
@endif

<div class="row g-3">
    <div class="col-12 col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <div class="text-muted small">Status Saat Ini</div>
                        <span class="badge bg-{{ $complaint->status_badge }} fs-6 mt-1">
                            {{ $complaint->status_label }}
                        </span>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">Teknisi</div>
                        <div class="fw-semibold">{{ $complaint->teknisi->name ?? '-' }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('it.complaints.update-status', $complaint->id) }}"
                      class="d-flex gap-2 flex-wrap">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm" style="max-width: 220px;">
                        @foreach (['baru','diproses','menunggu_konfirmasi','selesai','ditolak'] as $s)
                            <option value="{{ $s }}" {{ $complaint->status === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-primary">Ubah Status</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><strong>Informasi Kendala (5W+1H)</strong></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th width="25%">What</th><td>{{ $complaint->what }}</td></tr>
                    <tr><th>Who</th><td>{{ $complaint->who }}</td></tr>
                    <tr><th>When</th><td>{{ $complaint->when?->format('d M Y H:i') }}</td></tr>
                    <tr><th>Where</th><td>{{ $complaint->where }}</td></tr>
                    <tr><th>Why</th><td>{{ $complaint->why ?: '-' }}</td></tr>
                    <tr><th>How</th><td>{{ $complaint->how }}</td></tr>
                    <tr><th>Deskripsi</th><td>{{ $complaint->deskripsi }}</td></tr>
                </table>
            </div>
        </div>

        @if ($complaint->handling)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Penanganan</strong>
                    @if ($complaint->status !== 'selesai')
                        <a href="{{ route('it.handling.create', $complaint->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><th width="25%">Analisis Penyebab</th><td>{{ $complaint->handling->analisis_penyebab }}</td></tr>
                        <tr><th>Tindakan</th><td>{{ $complaint->handling->tindakan }}</td></tr>
                        <tr><th>Solusi</th><td>{{ $complaint->handling->solusi }}</td></tr>
                        <tr><th>Hasil</th>
                            <td>
                                @if ($complaint->handling->hasil_penanganan === 'berhasil')
                                    <span class="badge bg-success">Berhasil</span>
                                @else
                                    <span class="badge bg-danger">Tidak Berhasil</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Ditangani oleh</th><td>{{ $complaint->handling->teknisi->name ?? '-' }}</td></tr>
                        <tr><th>Tanggal</th><td>{{ $complaint->handling->tanggal_penanganan?->format('d M Y H:i') }}</td></tr>
                        @if ($complaint->handling->catatan_teknisi)
                            <tr><th>Catatan</th><td>{{ $complaint->handling->catatan_teknisi }}</td></tr>
                        @endif
                        <tr>
                            <th>Dipakai ulang</th>
                            <td>
                                <span class="badge bg-secondary">{{ $complaint->handling->digunakan_kembali }} kali</span>
                                <span class="badge bg-success">{{ $complaint->handling->berhasil_digunakan_kembali }} berhasil</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif

        @if ($complaint->attachments->isNotEmpty())
            <div class="card mb-3">
                <div class="card-header"><strong>Lampiran</strong></div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach ($complaint->attachments as $att)
                            <div class="col-12 col-md-4">
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank"
                                   class="btn btn-outline-secondary btn-sm w-100 text-truncate">
                                    <i class="bi bi-paperclip"></i> {{ $att->file_name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if ($similar->isNotEmpty())
            <div class="card mb-3 border-warning">
                <div class="card-header bg-warning bg-opacity-25">
                    <strong><i class="bi bi-exclamation-triangle"></i> Kendala Serupa Pernah Ditangani</strong>
                </div>
                <div class="card-body">
                    @foreach ($similar as $item)
                        @php $c = $item['complaint']; @endphp
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <div>
                                    <span class="badge bg-secondary">{{ $c->nomor_pengaduan }}</span>
                                    <span class="text-muted small ms-2">
                                        {{ $c->tanggal_selesai?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>
                                <span class="badge bg-primary">Skor: {{ $item['score'] }}</span>
                            </div>
                            <div class="mt-2 small">
                                <div><strong>Kendala:</strong> {{ $c->what }}</div>
                                <div><strong>Penyebab:</strong> {{ $c->handling->analisis_penyebab ?? '-' }}</div>
                                <div class="mt-1 p-2 bg-light rounded">
                                    <strong>Solusi:</strong> {{ $c->handling->solusi ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="col-12 col-md-4">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Info Perangkat</strong>
                <a href="{{ route('it.pcs.history', $complaint->pc_id) }}"
                   class="btn btn-sm btn-outline-primary">
                    Riwayat
                </a>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <div class="text-muted small">Kode</div>
                    <div class="fw-semibold">{{ $complaint->pc->kode_pc ?? '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Nama</div>
                    <div>{{ $complaint->pc->nama_pc ?? '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Departemen</div>
                    <div>{{ $complaint->pc->departemen ?? '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Lokasi</div>
                    <div>{{ $complaint->pc->lokasi ?? '-' }}</div>
                </div>
                <div class="mb-0">
                    <div class="text-muted small">Kategori</div>
                    <div>{{ $complaint->category->nama_kategori ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Riwayat Perangkat Ini</strong></div>
            <div class="card-body p-0">
                @if ($riwayatPc->isEmpty())
                    <div class="text-muted text-center py-3 small">
                        Belum ada riwayat lain.
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($riwayatPc as $r)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div class="small">
                                        <div class="fw-semibold">{{ $r->jenis_kendala }}</div>
                                        <div class="text-muted">{{ $r->created_at->format('d M Y') }}</div>
                                    </div>
                                    <span class="badge bg-{{ $r->status_badge }}">
                                        {{ $r->status_label }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection