@extends('layouts.app')

@section('title', 'Data User')

@section('content')

<div class="page-header">
    <div>
        <h4>Data User</h4>
        <p>Kelola akun pelapor dan teknisi IT</p>
    </div>
    <a href="{{ route('it.users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($users->isEmpty())
            <div class="empty-state">
                <i class="bi bi-people empty-icon"></i>
                <h6>Belum Ada User</h6>
                <p>Tambahkan user untuk mulai mengelola</p>
                <a href="{{ route('it.users.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Tambah User
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="d-none d-md-table-cell">Departemen</th>
                            <th class="d-none d-lg-table-cell">Telepon</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $u->name }}
                                    @if ($u->id === auth()->id())
                                        <span class="badge bg-info ms-1">Anda</span>
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $u->email }}</td>
                                <td>
                                    @if ($u->role === 'it')
                                        <span class="badge bg-primary">IT</span>
                                    @else
                                        <span class="badge bg-secondary">Pelapor</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ $u->department ?? '-' }}</td>
                                <td class="d-none d-lg-table-cell">{{ $u->phone ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('it.users.edit', $u->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('it.users.destroy', $u->id) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin hapus user {{ $u->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $users->links() }}</div>
        @endif
    </div>
</div>

@endsection