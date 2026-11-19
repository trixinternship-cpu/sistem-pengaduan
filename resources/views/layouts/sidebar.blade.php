@php
    $role = auth()->user()->role;
@endphp

<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-identity">
            <img src="{{ asset('images/logo-trix.png') }}" alt="Logo TRIX">
            <div class="sidebar-brand-copy">
                <span class="sidebar-brand-name">Helpdesk TRIX</span>
                <span class="sidebar-brand-caption">INFORMATION SYSTEM</span>
            </div>
        </div>
        <button type="button" class="sidebar-menu-toggle" id="toggleSidebar"
                aria-label="Buka atau tutup sidebar" aria-controls="sidebar"
                aria-expanded="true" title="Buka / Tutup Menu">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <div class="sidebar-inner">
        {{-- =============== MENU PELAPOR =============== --}}
        @if ($role === 'pelapor')
            <div class="nav-section">
                <span class="nav-section-text">Menu Utama</span>
                <div class="nav-section-line"></div>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pelapor.dashboard') ? 'active' : '' }}"
                       href="{{ route('pelapor.dashboard') }}" title="Dashboard">
                        <i class="bi bi-grid-1x2"></i> 
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pelapor.complaints.create') ? 'active' : '' }}"
                       href="{{ route('pelapor.complaints.create') }}" title="Buat Pengaduan">
                        <i class="bi bi-plus-circle-dotted"></i> 
                        <span class="nav-text">Buat Pengaduan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pelapor.complaints.index') ? 'active' : '' }}"
                       href="{{ route('pelapor.complaints.index') }}" title="Riwayat Pengaduan">
                        <i class="bi bi-clock-history"></i> 
                        <span class="nav-text">Riwayat Pengaduan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}"
                       href="{{ route('chat.index') }}" title="Chat">
                        <i class="bi bi-chat-dots"></i> 
                        <span class="nav-text">Chat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}"
                       href="{{ route('archives.index') }}" title="Arsip Pengaduan">
                        <i class="bi bi-archive"></i>
                        <span class="nav-text">Arsip Pengaduan</span>
                    </a>
                </li>
            </ul>
        @endif

        {{-- =============== MENU IT =============== --}}
        @if ($role === 'it')
            <div class="nav-section">
                <span class="nav-section-text">Operasional</span>
                <div class="nav-section-line"></div>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('it.dashboard') ? 'active' : '' }}"
                       href="{{ route('it.dashboard') }}" title="Dashboard">
                        <i class="bi bi-grid-1x2"></i> 
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('it.complaints.*') ? 'active' : '' }}"
                       href="{{ route('it.complaints.index') }}" title="Pengaduan">
                        <i class="bi bi-inbox"></i> 
                        <span class="nav-text">Pengaduan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}"
                       href="{{ route('chat.index') }}" title="Chat">
                        <i class="bi bi-chat-dots"></i> 
                        <span class="nav-text">Chat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('archives.*') ? 'active' : '' }}"
                       href="{{ route('archives.index') }}" title="Arsip Pengaduan">
                        <i class="bi bi-archive"></i>
                        <span class="nav-text">Arsip Pengaduan</span>
                    </a>
                </li>
            </ul>

            <div class="nav-section">
                <span class="nav-section-text">Master Data</span>
                <div class="nav-section-line"></div>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('it.pcs.*') ? 'active' : '' }}"
                       href="{{ route('it.pcs.index') }}" title="Data Perangkat">
                        <i class="bi bi-pc-display"></i> 
                        <span class="nav-text">Data Perangkat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('it.categories.*') ? 'active' : '' }}"
                       href="{{ route('it.categories.index') }}" title="Kategori">
                        <i class="bi bi-tags"></i> 
                        <span class="nav-text">Kategori</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('it.users.*') ? 'active' : '' }}"
                       href="{{ route('it.users.index') }}" title="User">
                        <i class="bi bi-people"></i> 
                        <span class="nav-text">User</span>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</nav>

{{-- ================= STYLE SIDEBAR MODERN ================= --}}
<style>
    /* Animasi Gradasi Warna */
    @keyframes sidebarGradient {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* TRIX Signature — Soft Corporate Sidebar */
    :root {
        --sidebar-border: rgba(255,255,255,.18);
        --sidebar-hover: rgba(255,255,255,.13);
        --sidebar-active-bg: rgba(255,255,255,.20);
        --sidebar-active: #ffffff;
        --sidebar-text: rgba(255,255,255,.88);
        --sidebar-text-strong: #ffffff;
        --sidebar-width-expanded: 260px;
        --sidebar-width-collapsed: 80px;
    }


    .sidebar-brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        min-height: 86px;
        margin: 0 14px 10px;
        padding: 10px 4px 16px;
        border-bottom: 1px solid rgba(255,255,255,.22);
        overflow: hidden;
        white-space: nowrap;
    }
    .sidebar-brand-identity {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
        overflow: hidden;
        transition: opacity .18s ease, width .22s ease;
    }
    .sidebar-brand img {
        display: block;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        object-fit: contain;
        padding: 5px;
        border-radius: 12px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 5px 14px rgba(22,58,125,.18);
    }
    .sidebar-brand-copy {
        display: flex;
        flex-direction: column;
        min-width: 0;
        transition: opacity .2s ease, width .2s ease;
    }
    .sidebar-brand-name {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -.25px;
    }
    .sidebar-brand-caption {
        margin-top: 3px;
        color: rgba(255,255,255,.72);
        font-size: 8px;
        font-weight: 750;
        letter-spacing: 1.25px;
    }
    body.sidebar-collapsed .sidebar-brand {
        justify-content: center;
        margin-left: 8px;
        margin-right: 8px;
        padding-left: 0;
        padding-right: 0;
    }
    body.sidebar-collapsed .sidebar-brand-identity { display: none; }
    body.sidebar-collapsed .sidebar-brand-copy {
        width: 0;
        opacity: 0;
        overflow: hidden;
    }
    body.sidebar-collapsed .sidebar-brand img { display: none; }

    .sidebar {
        width: var(--sidebar-width-expanded);
        /* BACKGROUND GRADASI BERANIMASI */
        background: linear-gradient(120deg, #1e3a8a, #2856b6, #3b82f6, #2856b6);
        background-size: 300% 300%;
        animation: sidebarGradient 12s ease infinite;
        
        border-right: 1px solid var(--sidebar-border);
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 1040;
        transition: width .3s cubic-bezier(.4,0,.2,1), transform .3s ease;
        display: flex;
        flex-direction: column;
        box-shadow: 8px 0 24px rgba(35,82,160,.18);
    }

    .sidebar-inner {
        padding: 17px 13px;
        overflow-y: auto;
        overflow-x: hidden;
        flex-grow: 1;
        scrollbar-width: thin;
        scrollbar-color: #526982 transparent;
    }
    .sidebar-inner::-webkit-scrollbar { width: 4px; }
    .sidebar-inner::-webkit-scrollbar-thumb {
        background: #526982;
        border-radius: 10px;
    }

    /* Hamburger berada di dalam sidebar */
    .sidebar-menu-toggle {
        display: inline-flex;
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(255,255,255,.28);
        border-radius: 10px;
        color: #fff;
        background: rgba(255,255,255,.15);
        box-shadow: 0 4px 10px rgba(22,58,125,.13);
        cursor: pointer;
        transition: background .18s ease, transform .18s ease;
    }
    .sidebar-menu-toggle i { font-size: 21px; line-height: 1; }
    .sidebar-menu-toggle:hover {
        color: #fff;
        background: rgba(255,255,255,.25);
        transform: translateY(-1px);
    }
    body.sidebar-collapsed .sidebar-brand {
        min-height: 76px;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    body.sidebar-collapsed .sidebar-menu-toggle {
        flex-basis: 42px;
        width: 42px;
        height: 42px;
    }

    /* Section heading */
    .sidebar .nav-section {
        margin: 20px 11px 10px;
        display: flex;
        align-items: center;
        gap: 9px;
        overflow: hidden;
        white-space: nowrap;
    }
    .sidebar .nav-section:first-child { margin-top: 7px; }
    .nav-section-text {
        color: rgba(255,255,255,.68);
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.45px;
        transition: opacity .25s ease;
    }
    .nav-section-line {
        flex-grow: 1;
        height: 1px;
        background: rgba(255,255,255,.22);
    }

    /* Navigation items */
    .sidebar .nav {
        gap: 4px;
    }
    .sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 44px;
        padding: 10px 13px;
        margin-bottom: 2px;
        border: 1px solid transparent;
        border-radius: 8px;
        color: var(--sidebar-text);
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
        transition: background .2s ease, color .2s ease, border-color .2s ease, transform .2s ease;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }
    .sidebar .nav-link i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        min-width: 22px;
        color: rgba(255,255,255,.78);
        font-size: 17px;
        text-align: center;
        transition: color .2s ease, transform .2s ease;
    }
    .nav-text {
        transition: opacity .2s ease, transform .2s ease;
    }
    .sidebar .nav-link:hover {
        border-color: rgba(255,255,255,.22);
        background: var(--sidebar-hover);
        color: var(--sidebar-text-strong);
        transform: translateX(2px);
    }
    .sidebar .nav-link:hover i {
        color: #ffffff;
        transform: translateY(-1px);
    }
    .sidebar .nav-link.active {
        border-color: rgba(255,255,255,.25);
        background: var(--sidebar-active-bg);
        color: #ffffff;
        font-weight: 750;
        box-shadow: inset 3px 0 0 #ffffff, 0 5px 14px rgba(20,58,130,.16);
    }
    .sidebar .nav-link.active i { color: #ffffff; }

    /* Collapsed state */
    body.sidebar-collapsed .sidebar {
        width: var(--sidebar-width-collapsed);
    }
    body.sidebar-collapsed .nav-text,
    body.sidebar-collapsed .nav-section-text {
        opacity: 0;
        pointer-events: none;
        width: 0;
    }
    body.sidebar-collapsed .nav-section-line { display: none; }
    body.sidebar-collapsed .sidebar .nav-link {
        justify-content: center;
        padding: 11px 0;
    }
    body.sidebar-collapsed .sidebar .nav-link i {
        margin: 0;
        font-size: 19px;
    }
    body.sidebar-collapsed .sidebar .nav-link:hover::after {
        content: attr(title);
        position: absolute;
        left: calc(100% + 12px);
        top: 50%;
        transform: translateY(-50%);
        background: #214fae;
        color: #ffffff;
        padding: 7px 11px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 6px;
        font-size: 11px;
        font-weight: 650;
        white-space: nowrap;
        box-shadow: 0 5px 14px rgba(39,53,70,.15);
        z-index: 1050;
        pointer-events: none;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .sidebar {
            top: 0;
            height: 100dvh;
            transform: translateX(-100%);
            width: var(--sidebar-width-expanded) !important;
            box-shadow: 12px 0 28px rgba(39,53,70,.12);
            z-index: 1060; 
        }
        .sidebar.show { transform: translateX(0); }
        body.sidebar-collapsed .sidebar-brand {
            justify-content: space-between;
            margin-left: 14px;
            margin-right: 14px;
            padding-left: 4px;
            padding-right: 4px;
        }
        body.sidebar-collapsed .sidebar-brand-identity {
            display: flex;
            width: auto;
            opacity: 1;
        }
        body.sidebar-collapsed .sidebar-brand img {
            display: block;
            width: 48px;
            height: 48px;
            flex-basis: 48px;
        }
        body.sidebar-collapsed .sidebar-brand-copy {
            width: auto;
            opacity: 1;
            overflow: visible;
        }
        body.sidebar-collapsed .nav-text,
        body.sidebar-collapsed .nav-section-text {
            width: auto;
            opacity: 1;
            overflow: visible;
            pointer-events: auto;
        }
        body.sidebar-collapsed .nav-section-line { display: block; }
        body.sidebar-collapsed .sidebar .nav-link {
            justify-content: flex-start;
            padding: 10px 13px;
        }
        body.sidebar-collapsed .sidebar .nav-link i { font-size: 17px; }
        body.sidebar-collapsed .sidebar .nav-link:hover::after { display: none; }
    }

    @media (prefers-reduced-motion: reduce) {
        .sidebar, .sidebar *, .sidebar *::before, .sidebar *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>



<style>
@media (max-width: 768px) {
    .sidebar {
        z-index: 1060 !important;
        pointer-events: auto !important;
    }
    .sidebar.show {
        transform: translateX(0) !important;
        visibility: visible !important;
    }
    .sidebar .nav-link,
    .sidebar .sidebar-menu-toggle {
        pointer-events: auto !important;
        position: relative;
        z-index: 1;
    }
}
</style>