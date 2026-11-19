@php
    $role = auth()->user()->role;
@endphp

<nav class="sidebar" id="sidebar">

    {{-- =============== MENU PELAPOR =============== --}}
    @if ($role === 'pelapor')
        <div class="nav-section">Menu Utama</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelapor.dashboard') ? 'active' : '' }}"
                   href="{{ route('pelapor.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelapor.complaints.create') ? 'active' : '' }}"
                   href="{{ route('pelapor.complaints.create') }}">
                    <i class="bi bi-plus-circle"></i> Buat Pengaduan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelapor.complaints.index') ? 'active' : '' }}"
                   href="{{ route('pelapor.complaints.index') }}">
                    <i class="bi bi-clock-history"></i> Riwayat Pengaduan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}"
                   href="{{ route('chat.index') }}">
                    <i class="bi bi-chat-dots"></i> Chat
                </a>
            </li>
        </ul>
    @endif

    {{-- =============== MENU IT =============== --}}
    @if ($role === 'it')
        <div class="nav-section">Operasional</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('it.dashboard') ? 'active' : '' }}"
                   href="{{ route('it.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('it.complaints.*') ? 'active' : '' }}"
                   href="{{ route('it.complaints.index') }}">
                    <i class="bi bi-inbox"></i> Pengaduan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}"
                   href="{{ route('chat.index') }}">
                    <i class="bi bi-chat-dots"></i> Chat
                </a>
            </li>
        </ul>

        <div class="nav-section">Master Data</div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('it.pcs.*') ? 'active' : '' }}"
                   href="{{ route('it.pcs.index') }}">
                    <i class="bi bi-pc-display-horizontal"></i> Data Perangkat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('it.categories.*') ? 'active' : '' }}"
                   href="{{ route('it.categories.index') }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('it.users.*') ? 'active' : '' }}"
                   href="{{ route('it.users.index') }}">
                    <i class="bi bi-people"></i> User
                </a>
            </li>
        </ul>
    @endif

</nav>