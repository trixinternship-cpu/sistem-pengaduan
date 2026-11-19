@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')

<div class="page-header">
    <div>
        <h4>{{ $complaint->nomor_pengaduan }}</h4>
        <p>Dibuat {{ $complaint->created_at->format('d M Y H:i') }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('chat.show', $complaint->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-chat-dots"></i> Chat
        </a>
        <a href="{{ route('pelapor.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if ($complaint->is_recurring)
    <div class="alert alert-danger">
        <i class="bi bi-arrow-repeat"></i>
        <strong>Kendala berulang.</strong>
        Pengaduan ini terkait dengan riwayat kendala sebelumnya.
    </div>
@endif

<div class="row g-3">
    <div class="col-12 col-md-8">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
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
        </div>

        <div class="card mb-3">
            <div class="card-header"><strong>Informasi Kendala (5W+1H)</strong></div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th width="30%">What (Apa)</th><td>{{ $complaint->what }}</td></tr>
                    <tr><th>Who (Siapa)</th><td>{{ $complaint->who }}</td></tr>
                    <tr><th>When (Kapan)</th><td>{{ $complaint->when?->format('d M Y H:i') }}</td></tr>
                    <tr><th>Where (Dimana)</th><td>{{ $complaint->where }}</td></tr>
                    <tr><th>Why (Penyebab)</th><td>{{ $complaint->why ?: '-' }}</td></tr>
                    <tr><th>How (Kronologi)</th><td>{{ $complaint->how }}</td></tr>
                    <tr><th>Deskripsi</th><td>{{ $complaint->deskripsi }}</td></tr>
                </table>
            </div>
        </div>

        @if ($complaint->handling)
            <div class="card mb-3">
                <div class="card-header"><strong>Penanganan oleh IT</strong></div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr><th width="30%">Analisis Penyebab</th><td>{{ $complaint->handling->analisis_penyebab }}</td></tr>
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
                    </table>
                </div>
            </div>
        @endif

        @if ($complaint->attachments->isNotEmpty())
            <div class="card">
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
    </div>

    <div class="col-12 col-md-4">
        <div class="card mb-3">
            <div class="card-header"><strong>Info Perangkat</strong></div>
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

        @if ($complaint->status === 'menunggu_konfirmasi' && $complaint->handling)
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <strong>Konfirmasi Solusi</strong>
                </div>
                <div class="card-body">
                    <p class="small mb-3">
                        Tim IT sudah memberikan solusi. Apakah solusi tersebut berhasil mengatasi kendala Anda?
                    </p>
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('pelapor.complaints.confirm-recurring', $complaint->id) }}">
                            @csrf
                            <input type="hidden" name="berhasil" value="1">
                            <input type="hidden" name="related_complaint_id" value="{{ $complaint->related_complaint_id ?? $complaint->id }}">
                            <button class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Ya, Berhasil
                            </button>
                        </form>
                        <form method="POST" action="{{ route('pelapor.complaints.confirm-recurring', $complaint->id) }}">
                            @csrf
                            <input type="hidden" name="berhasil" value="0">
                            <input type="hidden" name="related_complaint_id" value="{{ $complaint->related_complaint_id ?? $complaint->id }}">
                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle"></i> Tidak, Masih Bermasalah
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection