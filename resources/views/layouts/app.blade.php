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

            --sidebar-width: 260px;

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

            top: 0;

            left: var(--sidebar-width);

            right: 0;

            z-index: 1030;

            display: flex;

            align-items: center;

            padding: 0 1.5rem;

            transition: left .3s cubic-bezier(.4,0,.2,1);

        }

        body.sidebar-collapsed .topbar { left: 80px; }

        .mobile-sidebar-toggle {

            display: none;

            flex: 0 0 40px;

            width: 40px;

            height: 40px;

            align-items: center;

            justify-content: center;

            border: 1px solid #dce4ed;

            border-radius: 10px;

            color: #fff;

            background: #2d63cc;

            box-shadow: 0 4px 10px rgba(35,82,160,.16);

            cursor: pointer;

        }

        .mobile-sidebar-toggle i { font-size: 22px; line-height: 1; }

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

            transition: margin-left .3s cubic-bezier(.4,0,.2,1);

        }

        body.sidebar-collapsed .content-wrapper { margin-left: 80px; }



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



        /* =============== PREMIUM GLOBAL PAGE LOADER =============== */
        #globalPageLoader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 18% 18%, rgba(67, 130, 255, .28), transparent 34%),
                radial-gradient(circle at 82% 78%, rgba(35, 91, 205, .24), transparent 36%),
                rgba(9, 25, 58, .78);
            -webkit-backdrop-filter: blur(13px);
            backdrop-filter: blur(13px);
            transition: opacity .28s ease, visibility .28s ease;
        }

        #globalPageLoader.show {
            visibility: visible;
            opacity: 1;
            pointer-events: all;
        }

        #globalPageLoader .loader-card {
            position: relative;
            width: min(390px, 100%);
            padding: 38px 38px 30px;
            overflow: hidden;
            text-align: center;
            border: 1px solid rgba(255,255,255,.72);
            border-radius: 24px;
            background: linear-gradient(145deg, rgba(255,255,255,.98), rgba(242,247,255,.96));
            box-shadow: 0 28px 90px rgba(0, 15, 48, .32), 0 5px 18px rgba(0, 38, 112, .10);
            transform: translateY(14px) scale(.97);
            transition: transform .35s cubic-bezier(.2,.8,.2,1);
        }

        #globalPageLoader.show .loader-card {
            transform: translateY(0) scale(1);
        }

        #globalPageLoader .loader-card::before,
        #globalPageLoader .loader-card::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(2px);
        }

        #globalPageLoader .loader-card::before {
            top: -125px;
            right: -85px;
            background: radial-gradient(circle, rgba(72,132,255,.17), transparent 70%);
        }

        #globalPageLoader .loader-card::after {
            bottom: -145px;
            left: -100px;
            background: radial-gradient(circle, rgba(0,51,160,.12), transparent 70%);
        }

        #globalPageLoader .loader-brand {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 27px;
            color: #17366f;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1.25px;
        }

        #globalPageLoader .loader-brand-mark {
            display: grid;
            width: 30px;
            height: 30px;
            place-items: center;
            border-radius: 9px;
            color: #fff;
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -1px;
            background: linear-gradient(145deg, #4b83ee, #0033a0 72%);
            box-shadow: 0 5px 13px rgba(0,51,160,.25);
        }

        #globalPageLoader .loader-visual {
            position: relative;
            display: grid;
            width: 112px;
            height: 112px;
            margin: 0 auto 24px;
            place-items: center;
        }

        #globalPageLoader .loader-orbit {
            position: absolute;
            inset: 3px;
            border: 1.5px solid rgba(0, 51, 160, .13);
            border-top-color: #1456cb;
            border-right-color: rgba(20, 86, 203, .55);
            border-radius: 50%;
            animation: trixOrbit 1.25s linear infinite;
        }

        #globalPageLoader .loader-orbit.orbit-two {
            inset: 13px;
            border: 1.5px dashed rgba(50, 112, 220, .38);
            animation: trixOrbitReverse 2.2s linear infinite;
        }

        #globalPageLoader .loader-core {
            display: grid;
            width: 66px;
            height: 66px;
            place-items: center;
            border: 1px solid rgba(255,255,255,.95);
            border-radius: 21px;
            color: #fff;
            font-size: 25px;
            font-weight: 900;
            background: linear-gradient(145deg, #4f8af5 0%, #1454c4 55%, #002b88 100%);
            box-shadow: 0 10px 25px rgba(0, 51, 160, .27), inset 0 1px 0 rgba(255,255,255,.4);
            animation: trixCorePulse 1.8s ease-in-out infinite;
        }

        #globalPageLoader .loader-spark {
            position: absolute;
            width: 8px;
            height: 8px;
            border: 2px solid #fff;
            border-radius: 50%;
            background: #4b83ee;
            box-shadow: 0 0 0 3px rgba(75,131,238,.13);
        }

        #globalPageLoader .loader-spark.spark-one { top: 13px; right: 15px; }
        #globalPageLoader .loader-spark.spark-two { bottom: 17px; left: 9px; width: 6px; height: 6px; }

        #globalPageLoader .loader-heading {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #142b50;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.35px;
        }

        #globalPageLoader .loader-description {
            position: relative;
            z-index: 1;
            margin: 8px 0 22px;
            color: #71819a;
            font-size: 12.5px;
            line-height: 1.6;
        }

        #globalPageLoader .loader-progress {
            position: relative;
            z-index: 1;
            height: 5px;
            overflow: hidden;
            border-radius: 20px;
            background: #e5ecf8;
        }

        #globalPageLoader .loader-progress span {
            display: block;
            width: 38%;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #75a5ff, #1456cb, #75a5ff);
            box-shadow: 0 0 12px rgba(20,86,203,.28);
            animation: trixProgress 1.35s ease-in-out infinite;
        }

        #globalPageLoader .loader-footer {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 17px;
            color: #9aa8bd;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.45px;
            text-transform: uppercase;
        }

        #globalPageLoader .loader-footer i {
            color: #2f6bd1;
            font-size: 11px;
        }

        @keyframes trixOrbit { to { transform: rotate(360deg); } }
        @keyframes trixOrbitReverse { to { transform: rotate(-360deg); } }
        @keyframes trixCorePulse {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 25px rgba(0,51,160,.27), inset 0 1px 0 rgba(255,255,255,.4); }
            50% { transform: scale(1.055); box-shadow: 0 13px 30px rgba(0,51,160,.34), inset 0 1px 0 rgba(255,255,255,.5); }
        }
        @keyframes trixProgress {
            0% { transform: translateX(-115%); }
            100% { transform: translateX(290%); }
        }

        @media (max-width: 480px) {
            #globalPageLoader .loader-card { padding: 32px 25px 25px; border-radius: 21px; }
            #globalPageLoader .loader-visual { width: 100px; height: 100px; }
        }

        @media (prefers-reduced-motion: reduce) {
            #globalPageLoader *, #globalPageLoader *::before, #globalPageLoader *::after {
                animation-duration: 2.5s !important;
                transition-duration: .01ms !important;
            }
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

            .topbar .brand img { height: 64px; }

            .topbar .brand-text { font-size: 1.1rem; }

        }

        @media (max-width: 768px) {

            :root { --topbar-height: 72px; --sidebar-width: 260px; }

            .topbar {

                left: 0 !important;

                width: 100%;

                height: var(--topbar-height);

                padding: 0 .85rem;

                gap: .65rem;

            }

            .mobile-sidebar-toggle { display: inline-flex; }

            .topbar .user-info { gap: .4rem; margin-left: auto; }

            .topbar .clock { padding: .35rem .5rem; font-size: .72rem; }

            .topbar .clock .date { display: none; }

            .topbar .user-info .user-name,

            .topbar .user-info .user-role { display: none; }

            .topbar .user-info .btn { padding: .35rem .55rem; font-size: .72rem; }

            .sidebar {

                top: 0 !important;

                left: 0;

                bottom: 0;

                width: 260px !important;

                max-width: min(82vw, 300px);

                height: 100dvh;

                transform: translateX(-105%);

                z-index: 1050;

                overflow-y: auto;

                overscroll-behavior: contain;

            }

            .sidebar.show { transform: translateX(0); }

            body.sidebar-collapsed .sidebar { width: 260px !important; }

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

            .content-wrapper {

                margin-left: 0 !important;

                width: 100%;

                min-width: 0;

                padding: calc(var(--topbar-height) + 1rem) .85rem 1.5rem;

            }

            body.sidebar-collapsed .content-wrapper { margin-left: 0 !important; }

            .sidebar-backdrop {

                display: none;

                position: fixed;

                inset: 0;

                background: rgba(16,29,48,.48);

                z-index: 1045;

            }

            .sidebar-backdrop.show { display: block; }

            #notifList { width: min(300px, calc(100vw - 24px)); }

            .page-header { flex-direction: column; align-items: stretch; }

            .page-header .btn { width: 100%; }

            .stat-card { padding: .85rem 1rem; gap: .75rem; }

            .stat-icon { width: 40px; height: 40px; font-size: 1.15rem; }

            .stat-value { font-size: 1.25rem; }

            .stat-label { font-size: .7rem; }

            .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }

            .table { min-width: 620px; }

            .card-body { padding: 1rem; }

            .card-header { padding: .85rem 1rem; }

        }

        @media (max-width: 480px) {

            :root { --topbar-height: 66px; }

            .topbar { padding: 0 .55rem; gap: .4rem; }

            .mobile-sidebar-toggle { width: 36px; height: 36px; flex-basis: 36px; }

            .topbar .clock { display: none; }

            .topbar .user-info { gap: .3rem; }

            .topbar .user-info .btn { font-size: .68rem; }

            .content-wrapper { padding: calc(var(--topbar-height) + .75rem) .65rem 1.25rem; }

            .card-body { padding: .85rem; }

        }

        </style>

    @stack('styles')

</head>

<body>

    <!-- Premium global page loading untuk seluruh halaman -->
    <div id="globalPageLoader" aria-live="polite" aria-busy="true" aria-hidden="true">
        <div class="loader-card">
            <div class="loader-brand">
                <span class="loader-brand-mark">T</span>
                <span>HELPDESK TRIX</span>
            </div>

            <div class="loader-visual" aria-hidden="true">
                <span class="loader-orbit"></span>
                <span class="loader-orbit orbit-two"></span>
                <span class="loader-core">T</span>
                <span class="loader-spark spark-one"></span>
                <span class="loader-spark spark-two"></span>
            </div>

            <h2 class="loader-heading">Sedang menyiapkan halaman</h2>
            <p class="loader-description" id="globalLoaderText">Mohon tunggu sebentar, kami sedang memuat informasi untuk Anda.</p>

            <div class="loader-progress" aria-hidden="true"><span></span></div>
            <div class="loader-footer"><i class="bi bi-shield-check"></i> TRIX INFORMATION SYSTEM</div>
        </div>
    </div>




    <div class="topbar">

        <button type="button" class="mobile-sidebar-toggle" id="toggleSidebarMobile"

                aria-label="Buka menu navigasi" aria-controls="sidebar"

                aria-expanded="false" title="Buka Menu">

            <i class="bi bi-list"></i>

        </button>

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

        /* ============ RESPONSIVE SIDEBAR ============ */

        const desktopToggle = document.getElementById('toggleSidebar');

        const mobileToggle = document.getElementById('toggleSidebarMobile');

        const sidebar = document.querySelector('.sidebar');

        const backdrop = document.getElementById('sidebarBackdrop');

        const contentWrapper = document.querySelector('.content-wrapper');

        const desktopBreakpoint = 768;



        function setDesktopCollapsed(collapsed) {

            document.body.classList.toggle('sidebar-collapsed', collapsed);

            localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');

            if (contentWrapper) contentWrapper.style.marginLeft = collapsed ? '80px' : '260px';

            if (desktopToggle) {

                desktopToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');

                desktopToggle.setAttribute('title', collapsed ? 'Buka Menu' : 'Tutup Menu');

            }

        }

        function openMobileSidebar() {

            if (sidebar) sidebar.classList.add('show');

            if (backdrop) backdrop.classList.add('show');

            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'true');

        }

        function closeMobileSidebar() {

            if (sidebar) sidebar.classList.remove('show');

            if (backdrop) backdrop.classList.remove('show');

            if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');

        }



        if (window.innerWidth > desktopBreakpoint) {

            setDesktopCollapsed(localStorage.getItem('sidebarCollapsed') === 'true');

        } else {

            if (contentWrapper) contentWrapper.style.marginLeft = '0';

            closeMobileSidebar();

        }

        if (desktopToggle) desktopToggle.addEventListener('click', function () {

            if (window.innerWidth > desktopBreakpoint) {

                setDesktopCollapsed(!document.body.classList.contains('sidebar-collapsed'));

            }

        });

        if (mobileToggle) mobileToggle.addEventListener('click', function () {

            if (sidebar && sidebar.classList.contains('show')) closeMobileSidebar();

            else openMobileSidebar();

        });

        if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') closeMobileSidebar();

        });

        if (sidebar) sidebar.querySelectorAll('a.nav-link').forEach(function (link) {

            link.addEventListener('click', function () {

                if (window.innerWidth <= desktopBreakpoint) closeMobileSidebar();

            });

        });

        window.addEventListener('resize', function () {

            if (window.innerWidth <= desktopBreakpoint) {

                if (contentWrapper) contentWrapper.style.marginLeft = '0';

                document.body.classList.remove('sidebar-collapsed');

                closeMobileSidebar();

            } else {

                closeMobileSidebar();

                setDesktopCollapsed(localStorage.getItem('sidebarCollapsed') === 'true');

            }

        });



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



                /* ============ GLOBAL PAGE LOADING ============ */
        (function () {
            const loader = document.getElementById('globalPageLoader');
            const loaderText = document.getElementById('globalLoaderText');

            if (!loader) return;

            let navigationStarted = false;
            const pendingForms = new WeakSet();

            function showPageLoading(message) {
                if (loaderText) {
                    loaderText.textContent = message || 'Memuat halaman...';
                }

                loader.classList.add('show');
            }

            function hidePageLoading() {
                loader.classList.remove('show');
                navigationStarted = false;
            }

            // Navigasi semua link internal, termasuk menu sidebar dan link yang dibuat dinamis.
            document.addEventListener('click', function (event) {
                const target = event.target;
                if (!(target instanceof Element)) return;

                const link = target.closest('a');
                if (!link) return;

                if (
                    event.defaultPrevented ||
                    event.button !== 0 ||
                    event.ctrlKey || event.shiftKey || event.altKey || event.metaKey ||
                    link.target === '_blank' ||
                    link.hasAttribute('download') ||
                    link.hasAttribute('data-no-loading') ||
                    link.classList.contains('dropdown-toggle')
                ) {
                    return;
                }

                const href = link.getAttribute('href');
                if (
                    !href ||
                    href === '#' ||
                    href.startsWith('javascript:') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:')
                ) {
                    return;
                }

                let destination;
                try {
                    destination = new URL(href, window.location.href);
                } catch (error) {
                    return;
                }

                if (destination.origin !== window.location.origin) return;

                // Abaikan link yang hanya mengubah posisi/fragmen di halaman yang sama.
                const current = new URL(window.location.href);
                const sameDocument =
                    destination.pathname === current.pathname &&
                    destination.search === current.search &&
                    destination.hash !== '';

                if (sameDocument || destination.href === current.href) return;

                // Tunda navigasi sebentar agar browser sempat menampilkan overlay.
                event.preventDefault();
                if (navigationStarted) return;

                navigationStarted = true;
                showPageLoading('Mohon tunggu sebentar, kami sedang memuat informasi untuk Anda.');

                window.setTimeout(function () {
                    window.location.assign(destination.href);
                }, 120);
            });

            // Loading untuk form biasa. Form AJAX dapat dikecualikan dengan data-no-loading.
            document.addEventListener('submit', function (event) {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) return;

                if (pendingForms.has(form)) {
                    pendingForms.delete(form);
                    return;
                }

                if (
                    event.defaultPrevented ||
                    form.hasAttribute('data-no-loading') ||
                    form.classList.contains('no-global-loading')
                ) {
                    return;
                }

                // Submit event sudah berjalan, termasuk validasi browser.
                // requestSubmit mempertahankan tombol submit dan nilai name/value-nya.
                event.preventDefault();

                const submitter = event.submitter;
                showPageLoading('Permintaan Anda sedang diproses. Mohon tunggu sebentar.');

                const button = submitter || form.querySelector(
                    'button[type="submit"], input[type="submit"]'
                );

                if (button && button.tagName === 'BUTTON') {
                    button.classList.add('btn-loading');
                }

                window.setTimeout(function () {
                    pendingForms.add(form);

                    if (typeof form.requestSubmit === 'function') {
                        if (submitter && submitter.form === form) {
                            form.requestSubmit(submitter);
                        } else {
                            form.requestSubmit();
                        }
                    } else {
                        HTMLFormElement.prototype.submit.call(form);
                    }
                }, 120);
            });

            // Bersihkan overlay setelah halaman selesai dimuat atau saat kembali dari cache.
            window.addEventListener('pageshow', hidePageLoading);
        })();

</script>

    @stack('scripts')

</body>

</html>



<style>

@media (max-width: 768px) {

    #sidebarBackdrop {

        z-index: 1050 !important;

    }

    .sidebar {

        z-index: 1060 !important;

    }

}

</style>
