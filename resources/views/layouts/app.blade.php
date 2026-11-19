<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Helpdesk TRIX')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            --topbar-height: 108px;
            --primary: #0033a0;
            --primary-dark: #002270;
        }
        * { font-family: 'Segoe UI', Tahoma, sans-serif; }
        body {
            background-color: #f5f6fa;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* =============== ANIMASI =============== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .card { animation: fadeInUp 0.3s ease-out; }
        .alert { animation: slideInRight 0.35s ease-out; }
        .btn { transition: all 0.2s ease; }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0,0,0,.08);
        }
        .btn:active { transform: translateY(0); }
        .table tbody tr { transition: background-color 0.15s ease; }

        /* =============== TOPBAR =============== */
        .topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }
        .topbar .brand {
            display: flex;
            align-items: center;
            gap: .85rem;
            min-width: 0;
        }
                .topbar .brand img {
            height: 70px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
        }
        .topbar .brand-text {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.35rem;
            border-left: 2px solid #e5e7eb;
            padding-left: 1rem;
            margin-left: .25rem;
            white-space: nowrap;
        }
        .topbar .user-info {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-shrink: 0;
        }
        .topbar .clock {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: .85rem;
            color: #334155;
            white-space: nowrap;
        }
        .topbar .clock i { color: var(--primary); font-size: 1rem; }
        .topbar .clock .time { font-weight: 700; font-variant-numeric: tabular-nums; }
        .topbar .clock .date { color: #64748b; font-size: .78rem; }

        /* Notifikasi Bell */
        #notifBtn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: .4rem .6rem;
            position: relative;
            color: #334155;
            transition: all .15s;
        }
        #notifBtn:hover {
            background: #eff6ff;
            color: var(--primary);
            border-color: var(--primary);
        }
        #notifBtn i { font-size: 1.1rem; }
        #notifBadge {
            font-size: .6rem;
            padding: .25em .45em;
            position: absolute;
            top: -6px;
            right: -6px;
        }
        #notifList {
            width: 360px;
            max-height: 450px;
            overflow-y: auto;
            padding: 0;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 40px rgba(0,0,0,.12);
        }
        #notifList .dropdown-header {
            padding: .75rem 1rem;
            font-size: .85rem;
            color: #1e293b;
            background: #f8fafc;
            border-radius: 10px 10px 0 0;
        }
        .notif-item {
            padding: .75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background .15s;
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f8fafc; color: inherit; }
        .notif-item.unread {
            background: #eff6ff;
            border-left: 3px solid var(--primary);
        }
        .notif-item.unread:hover { background: #dbeafe; }
        .notif-item .notif-title {
            font-weight: 600;
            font-size: .85rem;
            color: #1e293b;
            margin-bottom: .15rem;
        }
        .notif-item .notif-msg {
            font-size: .78rem;
            color: #64748b;
            line-height: 1.3;
        }
        .notif-item .notif-time {
            font-size: .7rem;
            color: #94a3b8;
            margin-top: .25rem;
        }

        /* =============== SIDEBAR =============== */
        .sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            color: #cbd5e1;
            position: fixed;
            top: var(--topbar-height);
            bottom: 0; left: 0;
            overflow-y: auto;
            padding: 1.25rem 0;
            z-index: 1020;
            transition: transform .25s ease;
        }
        .sidebar .nav-section {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            padding: .75rem 1.25rem .25rem;
        }
        .sidebar .nav-section:first-child { padding-top: 0; }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: .65rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .92rem;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar .nav-link:hover { background: #334155; color: #fff; }
        .sidebar .nav-link.active {
            background: #334155;
            color: #fff;
            border-left-color: var(--primary);
        }
        .sidebar .nav-link i { font-size: 1.05rem; flex-shrink: 0; }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: var(--topbar-height);
            left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,.4);
            z-index: 1015;
        }
        .sidebar-backdrop.show { display: block; }

        /* =============== CONTENT =============== */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            padding: calc(var(--topbar-height) + 1.5rem) 1.5rem 2rem;
            min-height: 100vh;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,.05);
            border-radius: .6rem;
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem 1.25rem;
            border-radius: .6rem .6rem 0 0 !important;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            height: 100%;
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
        .stat-label { font-size: .8rem; color: #64748b; line-height: 1.2; }

        .table-responsive { border-radius: 0 0 .6rem .6rem; }
        .table { margin-bottom: 0; }
        .table thead th {
            font-size: .78rem;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: .03em;
            font-weight: 600;
            white-space: nowrap;
            padding: .75rem .5rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .table tbody td {
            padding: .75rem .5rem;
            vertical-align: middle;
            font-size: .875rem;
        }

        .btn-primary { background-color: var(--primary); border-color: var(--primary); }
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); }
        .btn-outline-primary:hover { background-color: var(--primary); border-color: var(--primary); }

        .badge { font-weight: 500; padding: .35em .6em; font-size: .75rem; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .page-header h4 { font-size: 1.25rem; font-weight: 700; margin-bottom: .25rem; }
        .page-header p { color: #64748b; font-size: .875rem; margin: 0; }

        .form-label { font-weight: 600; font-size: .85rem; color: #334155; margin-bottom: .4rem; }
        .form-control, .form-select {
            padding: .6rem .85rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: .9rem;
            transition: all .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
        }
        textarea.form-control { min-height: 80px; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state .empty-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            display: block;
        }
        .empty-state h6 {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: .5rem;
        }
        .empty-state p {
            color: #64748b;
            font-size: .875rem;
            margin-bottom: 1rem;
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }
        .loading-overlay .spinner-box {
            text-align: center;
            background: #fff;
            padding: 2rem 3rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,.15);
        }
        .loading-overlay .spinner-box .spinner-border {
            color: var(--primary);
            width: 3rem;
            height: 3rem;
        }
        .loading-overlay .spinner-box p {
            margin: 1rem 0 0 0;
            color: #64748b;
            font-size: .9rem;
        }
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%; left: 50%;
            margin-left: -8px; margin-top: -8px;
            border: 2px solid rgba(255,255,255,.5);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s linear infinite;
        }

        /* =============== RESPONSIVE =============== */
               @media (max-width: 991px) {
            :root { --sidebar-width: 220px; --topbar-height: 96px; }
                        .topbar .brand img { height: 72px; }
            .topbar .brand-text { font-size: 1.1rem; }
        }
        @media (max-width: 768px) {
            :root { --topbar-height: 84px; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .content-wrapper { margin-left: 0; padding: calc(var(--topbar-height) + 1rem) 1rem 2rem; }
            .topbar { padding: 0 1rem; }
            .topbar .brand-text { display: none; }
                        .topbar .brand img { height: 64px; }
            .topbar .clock .date { display: none; }
            .topbar .clock { padding: .35rem .7rem; font-size: .78rem; }
            #notifList { width: 300px; }
            .page-header { flex-direction: column; align-items: stretch; }
            .page-header .btn { width: 100%; }
            .stat-card { padding: .85rem 1rem; gap: .75rem; }
            .stat-icon { width: 40px; height: 40px; font-size: 1.15rem; }
            .stat-value { font-size: 1.25rem; }
            .stat-label { font-size: .7rem; }
            .table thead th { font-size: .7rem; padding: .5rem .4rem; }
            .table tbody td { padding: .55rem .4rem; font-size: .8rem; }
            .card-body { padding: 1rem; }
            .card-header { padding: .85rem 1rem; }
        }
        @media (max-width: 480px) {
            :root { --topbar-height: 76px; }
            .topbar { padding: 0 .75rem; }
            .topbar .brand img { height: 56px; }
            .content-wrapper { padding: calc(var(--topbar-height) + .75rem) .75rem 2rem; }
            .card-body { padding: .85rem; }
            .stat-card { padding: .75rem .85rem; gap: .6rem; }
            .stat-icon { width: 36px; height: 36px; font-size: 1rem; }
            .stat-value { font-size: 1.15rem; }
            .stat-label { font-size: .65rem; }
            .page-header h4 { font-size: 1rem; }
            .topbar .clock { padding: .3rem .55rem; font-size: .72rem; }
            .topbar .clock i { display: none; }
            #notifList { width: 280px; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="topbar">
        <button class="btn btn-sm btn-light d-md-none me-2" id="toggleSidebar">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="brand">
            <img src="{{ asset('images/logo-trix.png') }}" alt="TRIX">
            <span class="brand-text">Helpdesk TRIX</span>
        </span>
        <div class="user-info">
            <div class="clock d-none d-md-flex" id="liveClock">
                <i class="bi bi-clock"></i>
                <span class="time" id="clockTime">--:--:--</span>
                <span class="date" id="clockDate">--</span>
            </div>

            <div class="dropdown">
                <button id="notifBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                    <i class="bi bi-bell"></i>
                    <span class="badge rounded-pill bg-danger" id="notifBadge" style="display:none;">0</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" id="notifList">
                    <li class="dropdown-header d-flex justify-content-between align-items-center">
                        <strong>Notifikasi</strong>
                        <button class="btn btn-sm btn-link text-decoration-none p-0" id="markAllRead"
                                style="font-size: .72rem;">Tandai semua dibaca</button>
                    </li>
                    <li class="text-center text-muted py-3" id="notifEmpty" style="font-size: .85rem;">
                        <i class="bi bi-bell-slash d-block mb-1 fs-4"></i>
                        Belum ada notifikasi
                    </li>
                </ul>
            </div>

            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold" style="font-size:.9rem;">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size:.75rem;">
                    {{ auth()->user()->role === 'it' ? 'IT / Teknisi' : 'Pelapor' }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline">Logout</span>
                </button>
            </form>
        </div>
    </div>

    @include('layouts.sidebar')

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="content-wrapper">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ============ SIDEBAR ============ */
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
        }
        function closeSidebar() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
            });
        }
        if (backdrop) backdrop.addEventListener('click', closeSidebar);

        /* ============ JAM ============ */
        (function() {
            const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const pad = (n) => n < 10 ? '0' + n : n;

            function updateClock() {
                const now = new Date();
                const timeEl = document.getElementById('clockTime');
                const dateEl = document.getElementById('clockDate');
                if (timeEl) timeEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
                if (dateEl) dateEl.textContent = `${dayNames[now.getDay()]}, ${now.getDate()} ${monthNames[now.getMonth()]} ${now.getFullYear()}`;
            }
            updateClock();
            setInterval(updateClock, 1000);
        })();

        /* ============ NOTIFIKASI ============ */
        const notifBadge = document.getElementById('notifBadge');
        const notifList = document.getElementById('notifList');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        async function loadNotifications() {
            try {
                const res = await fetch('{{ route("notifications.index") }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                renderNotifications(data.notifications, data.unread_count);
            } catch (err) {
                console.error('Notif error:', err);
            }
        }

        function renderNotifications(items, unreadCount) {
            if (unreadCount > 0) {
                notifBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                notifBadge.style.display = 'inline-block';
            } else {
                notifBadge.style.display = 'none';
            }

            notifList.querySelectorAll('.notif-item, #notifEmpty').forEach(el => el.remove());

            if (items.length === 0) {
                const li = document.createElement('li');
                li.id = 'notifEmpty';
                li.className = 'text-center text-muted py-3';
                li.style.fontSize = '.85rem';
                li.innerHTML = '<i class="bi bi-bell-slash d-block mb-1 fs-4"></i>Belum ada notifikasi';
                notifList.appendChild(li);
                return;
            }

            items.forEach(n => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a href="${n.url}" class="notif-item ${n.is_read ? '' : 'unread'}" data-id="${n.id}">
                        <div class="notif-title">${n.title}</div>
                        <div class="notif-msg">${n.message}</div>
                        <div class="notif-time">${n.time_ago}</div>
                    </a>
                `;
                notifList.appendChild(li);
            });
        }

        const markAllBtn = document.getElementById('markAllRead');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', async function (e) {
                e.preventDefault();
                e.stopPropagation();
                try {
                    await fetch('{{ route("notifications.read-all") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });
                    loadNotifications();
                } catch (err) { console.error(err); }
            });
        }

        // Tandai semua dibaca saat dropdown dibuka
        const notifDropdownBtn = document.getElementById('notifBtn');
        if (notifDropdownBtn) {
            notifDropdownBtn.addEventListener('click', function () {
                setTimeout(async () => {
                    try {
                        await fetch('{{ route("notifications.read-all") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            }
                        });
                        notifBadge.style.display = 'none';
                        notifList.querySelectorAll('.notif-item.unread').forEach(el => {
                            el.classList.remove('unread');
                        });
                    } catch (err) { console.error(err); }
                }, 300);
            });
        }

        loadNotifications();
        setInterval(loadNotifications, 30000);

        /* ============ LOADING OVERLAY GLOBAL ============ */
        document.querySelectorAll('form.form-with-loading').forEach(form => {
            form.addEventListener('submit', function () {
                const overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="spinner-box">
                        <div class="spinner-border" role="status"></div>
                        <p>Memproses...</p>
                    </div>
                `;
                document.body.appendChild(overlay);
            });
        });

        // Loading state untuk tombol submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                if (btn && !btn.classList.contains('btn-loading') && !form.classList.contains('form-with-loading')) {
                    btn.classList.add('btn-loading');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>