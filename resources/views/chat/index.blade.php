@extends('layouts.app')

@section('title', 'Daftar Chat')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       DAFTAR CHAT — desain .pd-* (Konsisten dengan Dashboard)
    ============================================================ */
    @keyframes pdFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pdFloat {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-7px); }
    }
    @keyframes pdGradShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* ---- Hero Banner ---- */
    .pd-hero {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        padding: 28px 30px;
        margin-bottom: 26px;
        border-radius: 20px;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(120deg, #1e3a8a, #2856b6, #3b82f6, #2856b6);
        background-size: 300% 300%;
        animation: pdGradShift 14s ease infinite, pdFadeUp .6s ease both;
        box-shadow: 0 18px 40px -18px rgba(40,86,182,.55);
    }
    .pd-hero::before {
        content: '';
        position: absolute;
        width: 240px; height: 240px;
        top: -110px; right: -50px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.22), transparent 65%);
        animation: pdFloat 7s ease-in-out infinite;
        pointer-events: none;
    }
    .pd-hero::after {
        content: '';
        position: absolute;
        width: 160px; height: 160px;
        bottom: -90px; left: 20%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.14), transparent 65%);
        animation: pdFloat 9s ease-in-out infinite reverse;
        pointer-events: none;
    }
    .pd-hero__text { position: relative; z-index: 2; }
    .pd-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.22);
        margin-bottom: 12px;
    }
    .pd-hero h1 {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -.8px;
        margin: 0 0 6px;
        line-height: 1.15;
    }
    .pd-hero p {
        margin: 0;
        font-size: 14px;
        opacity: .88;
    }

    /* ---- Panel ---- */
    .pd-panel {
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        overflow: hidden;
        animation: pdFadeUp .6s cubic-bezier(.22,.8,.32,1) both;
        animation-delay: .15s;
        box-shadow: 0 4px 18px rgba(24,43,74,.04);
    }
    .pd-panel__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f6;
        background: linear-gradient(90deg, #fbfdff, #ffffff);
    }
    .pd-panel__title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 750;
        color: #1d2b42;
    }
    .pd-panel__title i {
        color: #2856b6;
        font-size: 18px;
    }

    /* ---- Chat List ---- */
    .pd-chat-list {
        display: flex;
        flex-direction: column;
    }
    .pd-chat-item {
        display: block;
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
        text-decoration: none;
        color: inherit;
        transition: background .18s ease, transform .18s ease;
        animation: pdFadeUp .45s ease both;
    }
    .pd-chat-item:nth-child(1) { animation-delay: .2s; }
    .pd-chat-item:nth-child(2) { animation-delay: .25s; }
    .pd-chat-item:nth-child(3) { animation-delay: .3s; }
    .pd-chat-item:nth-child(4) { animation-delay: .35s; }
    .pd-chat-item:nth-child(5) { animation-delay: .4s; }
    .pd-chat-item:last-child { border-bottom: 0; }
    
    .pd-chat-item:hover {
        background: #f6faff;
        transform: translateX(4px);
    }

    .pd-chat-nomor {
        font-weight: 750;
        font-size: 14px;
        color: #1d2b42;
        margin-bottom: 4px;
    }
    .pd-chat-meta {
        font-size: 12px;
        color: #8a97a8;
        margin-bottom: 8px;
    }
    .pd-chat-meta strong {
        color: #718096;
        font-weight: 650;
    }
    
    .pd-chat-message {
        font-size: 13px;
        color: #35445b;
        display: flex;
        gap: 6px;
        align-items: flex-start;
        background: #f8fafc;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #edf1f6;
    }
    .pd-chat-message strong {
        color: #2856b6;
        font-weight: 700;
        white-space: nowrap;
    }
    .pd-chat-message span {
        flex-grow: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pd-chat-time {
        font-size: 11.5px;
        color: #9db4d9;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .pd-chat-time i {
        font-size: 13px;
    }

    /* ---- Empty State ---- */
    .pd-empty {
        padding: 60px 20px;
        text-align: center;
        animation: pdFadeUp .5s ease both;
        animation-delay: .15s;
    }
    .pd-empty__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 88px; height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e8f0ff, #f6faff);
        color: #9db4d9;
        font-size: 38px;
        margin-bottom: 18px;
        animation: pdFloat 3.4s ease-in-out infinite;
    }
    .pd-empty h6 { font-weight: 750; color: #1d2b42; margin-bottom: 6px; font-size: 16px; }
    .pd-empty p { color: #718096; font-size: 13px; margin: 0; }

    /* ============================================================
       FIX PAGINATION (Sama seperti halaman Riwayat)
    ============================================================ */
    .pd-pagination {
        padding: 16px 20px;
        border-top: 1px solid #edf1f6;
        background: #fbfdff;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    .pd-pagination p.text-sm.text-gray-700,
    .pd-pagination .text-sm.text-gray-700,
    .pd-pagination .small.text-muted {
        display: none !important; 
    }
    .pd-pagination .pagination {
        margin: 0;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .pd-pagination .page-item {
        display: inline-flex;
    }
    .pd-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0;
        border: 1px solid #dce4ef;
        border-radius: 8px !important;
        color: #2856b6;
        font-size: 13px;
        font-weight: 600;
        background: #fff;
        transition: all .18s ease;
        line-height: 1;
    }
    .pd-pagination .page-link svg {
        display: none !important;
    }
    .pd-pagination .page-item:first-child .page-link {
        font-size: 0; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px;
    }
    .pd-pagination .page-item:last-child .page-link {
        font-size: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px;
    }
    .pd-pagination .page-item:first-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
    }
    .pd-pagination .page-item:last-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
    }
    .pd-pagination .page-item.disabled:first-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }
    .pd-pagination .page-item.disabled:last-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }
    .pd-pagination .page-item.active .page-link {
        background: #2856b6;
        border-color: #2856b6;
        color: #fff;
        box-shadow: 0 4px 10px -3px rgba(40,86,182,.5);
    }

    /* ---- Responsif ---- */
    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-chat-item { padding: 16px; }
        .pd-chat-message { flex-direction: column; gap: 4px; }
        .pd-chat-message span { white-space: normal; }
        .pd-pagination { align-items: center; }
        .pd-pagination .pagination { flex-wrap: wrap; justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-panel,
        .pd-chat-item, .pd-empty, .pd-empty__icon {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-chat-dots"></i> Pesan
        </span>
        <h1>Daftar Chat</h1>
        <p>Percakapan terkait pengaduan Anda.</p>
    </div>
</div>

{{-- ================= PANEL ================= --}}
<div class="pd-panel">
    <div class="pd-panel__head">
        <div class="pd-panel__title">
            <i class="bi bi-chat-left-text"></i> Percakapan
        </div>
    </div>

    @if ($chats->isEmpty())
        <div class="pd-empty">
            <div class="pd-empty__icon"><i class="bi bi-chat-dots"></i></div>
            <h6>Belum Ada Chat</h6>
            <p>Mulai chat dari halaman detail pengaduan.</p>
        </div>
    @else
        <div class="pd-chat-list">
            @foreach ($chats as $chat)
                <a href="{{ route('chat.show', $chat->complaint_id) }}" class="pd-chat-item">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div class="flex-grow-1 min-w-0">
                            <div class="pd-chat-nomor">
                                {{ $chat->complaint->nomor_pengaduan ?? '-' }}
                            </div>
                            <div class="pd-chat-meta">
                                <i class="bi bi-pc-display me-1"></i> 
                                <strong>{{ $chat->complaint->pc->kode_pc ?? '-' }}</strong> — 
                                {{ $chat->complaint->category->nama_kategori ?? '-' }}
                            </div>
                            
                            @if ($chat->latestMessage)
                                <div class="pd-chat-message mt-2">
                                    <strong>{{ $chat->latestMessage->sender_label }}:</strong>
                                    <span>{{ Str::limit($chat->latestMessage->message, 80) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-end">
                            @if ($chat->last_message_at)
                                <div class="pd-chat-time">
                                    <i class="bi bi-clock-history"></i> 
                                    {{ $chat->last_message_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="pd-pagination">
            {{ $chats->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection