@extends('layouts.app')

@section('title', $pc->exists ? 'Edit PC' : 'Tambah PC')

@section('content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold">{{ $pc->exists ? 'Edit PC' : 'Tambah PC Baru' }}</h4>
    <p class="text-muted mb-0">Isi data PC dengan lengkap</p>
</div>

<form method="POST"
      action="{{ $pc->exists ? route('it.pcs.update', $pc->id) : route('it.pcs.store') }}">
    @csrf
    @if ($pc->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kode PC <span class="text-danger">*</span></label>
                    <input type="text" name="kode_pc" class="form-control"
                           value="{{ old('kode_pc', $pc->kode_pc) }}"
                           placeholder="PC-001" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama PC <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pc" class="form-control"
                           value="{{ old('nama_pc', $pc->nama_pc) }}"
                           placeholder="PC Finance 01" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor Aset</label>
                    <input type="text" name="nomor_aset" class="form-control"
                           value="{{ old('nomor_aset', $pc->nomor_aset) }}"
                           placeholder="AST-001">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen <span class="text-danger">*</span></label>
                    <input type="text" name="departemen" class="form-control"
                           value="{{ old('departemen', $pc->departemen) }}"
                           placeholder="Finance" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" class="form-control"
                           value="{{ old('lokasi', $pc->lokasi) }}"
                           placeholder="Office Lantai 1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Pengguna</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Tidak ada --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}"
                                {{ old('user_id', $pc->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach (['aktif' => 'Aktif', 'rusak' => 'Rusak', 'maintenance' => 'Maintenance'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('status', $pc->status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control"
                              placeholder="Catatan tambahan...">{{ old('keterangan', $pc->keterangan) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('it.pcs.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
        </button>
    </div>
</form>

@endsection