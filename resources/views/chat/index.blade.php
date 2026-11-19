@extends('layouts.app')

@section('title', 'Daftar Chat')

@section('content')

<div class="page-header">
    <div>
        <h4>Daftar Chat</h4>
        <p>Percakapan terkait pengaduan</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if ($chats->isEmpty())
            <div class="empty-state">
                <i class="bi bi-chat-dots empty-icon"></i>
                <h6>Belum Ada Chat</h6>
                <p>Mulai chat dari halaman detail pengaduan</p>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($chats as $chat)
                    <li class="list-group-item">
                        <a href="{{ route('chat.show', $chat->complaint_id) }}" class="text-decoration-none text-dark">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold">{{ $chat->complaint->nomor_pengaduan ?? '-' }}</div>
                                    <div class="text-muted small">
                                        {{ $chat->complaint->pc->kode_pc ?? '-' }} —
                                        {{ $chat->complaint->category->nama_kategori ?? '-' }}
                                    </div>
                                    @if ($chat->latestMessage)
                                        <div class="text-muted small text-truncate mt-1">
                                            <strong>{{ $chat->latestMessage->sender_label }}:</strong>
                                            {{ Str::limit($chat->latestMessage->message, 60) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-end">
                                    @if ($chat->last_message_at)
                                        <div class="text-muted small">{{ $chat->last_message_at->diffForHumans() }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="p-3">{{ $chats->links() }}</div>
        @endif
    </div>
</div>

@endsection