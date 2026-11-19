@extends('layouts.app')

@section('title', 'Kategori Kendala')

@section('content')

<div class="page-header">
    <div>
        <h4>Kategori Kendala</h4>
        <p>Master kategori untuk klasifikasi pengaduan</p>
    </div>
    <a href="{{ route('it.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($categories->isEmpty())
            <div class="empty-state">
                <i class="bi bi-tags empty-icon"></i>
                <h6>Belum Ada Kategori</h6>
                <p>Buat kategori untuk klasifikasi pengaduan</p>
                <a href="{{ route('it.categories.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th class="d-none d-md-table-cell">Deskripsi</th>
                            <th>Jumlah</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $cat)
                            <tr>
                                <td class="fw-semibold">{{ $cat->nama_kategori }}</td>
                                <td class="text-muted small d-none d-md-table-cell">{{ $cat->deskripsi ?: '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $cat->complaints_count }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('it.categories.edit', $cat->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('it.categories.destroy', $cat->id) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus {{ $cat->nama_kategori }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
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
            <div class="p-3">{{ $categories->links() }}</div>
        @endif
    </div>
</div>

@endsection