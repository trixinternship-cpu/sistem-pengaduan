@extends('layouts.app')

@section('title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold">{{ $category->exists ? 'Edit Kategori' : 'Tambah Kategori' }}</h4>
    <p class="text-muted mb-0">Kategori digunakan untuk mengklasifikasi kendala</p>
</div>

<form method="POST"
      action="{{ $category->exists ? route('it.categories.update', $category->id) : route('it.categories.store') }}">
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama_kategori" class="form-control"
                       value="{{ old('nama_kategori', $category->nama_kategori) }}"
                       placeholder="Contoh: Hardware" required>
            </div>

            <div class="mb-0">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-control"
                          placeholder="Deskripsi singkat kategori...">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('it.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
        </button>
    </div>
</form>

@endsection