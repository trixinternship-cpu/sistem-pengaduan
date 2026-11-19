@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'Tambah User')

@section('content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold">{{ $user->exists ? 'Edit User' : 'Tambah User Baru' }}</h4>
    <p class="text-muted mb-0">Isi data user dengan lengkap</p>
</div>

<form method="POST"
      action="{{ $user->exists ? route('it.users.update', $user->id) : route('it.users.store') }}">
    @csrf
    @if ($user->exists)
        @method('PUT')
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Password
                        @if ($user->exists)
                            <small class="text-muted">(kosongkan jika tidak diubah)</small>
                        @else
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="password" name="password" class="form-control"
                           {{ $user->exists ? '' : 'required' }}>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="pelapor" {{ old('role', $user->role) === 'pelapor' ? 'selected' : '' }}>
                            Pelapor
                        </option>
                        <option value="it" {{ old('role', $user->role) === 'it' ? 'selected' : '' }}>
                            IT / Teknisi
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen</label>
                    <input type="text" name="department" class="form-control"
                           value="{{ old('department', $user->department) }}"
                           placeholder="Finance, HRD, IT, dll">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('it.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan
        </button>
    </div>
</form>

@endsection