@extends('layouts.app')

@section('title', 'Chat - ' . $complaint->nomor_pengaduan)

@section('content')

<div class="chat-container">

    {{-- Header Chat --}}
    <div class="card mb-3">
        <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div class="d-flex align-items-center gap-3">

                <div class="chat-header-logo">
                    <img src="{{ asset('images/logo-trix.png') }}" alt="TRIX">
                </div>

                <div>
                    <h6 class="mb-0 fw-bold">TRIX Assistant</h6>

                    <small class="text-muted">
                        <i class="bi bi-robot"></i>
                        AI Helpdesk •
                        {{ $complaint->nomor_pengaduan }}
                        —
                        {{ $complaint->pc->kode_pc ?? '-' }}
                    </small>
                </div>

            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">

                @if (auth()->user()->role === 'it')

                    <span class="badge bg-secondary">
                        <i class="bi bi-person"></i>
                        {{ $complaint->user->name ?? '-' }}
                    </span>

                @endif

                @if (auth()->user()->role === 'pelapor')

                    <span class="badge bg-info" id="aiQuotaBadge">
                        <i class="bi bi-robot"></i>
                        <span id="aiQuotaText">--</span>/50 hari ini
                    </span>

                @endif

                <a href="{{ auth()->user()->role === 'it'
                    ? route('it.complaints.show', $complaint->id)
                    : route('pelapor.complaints.show', $complaint->id) }}"
                   class="btn btn-sm btn-outline-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>

            </div>

        </div>
    </div>


    {{-- Area Chat --}}
    <div class="card">

        <div class="chat-messages" id="chatMessages"></div>


        {{-- Input --}}
        <div class="chat-input">

            <form id="chatForm" class="d-flex gap-2">

                @csrf

                <input type="text"
                       id="messageInput"
                       class="form-control"
                       placeholder="{{ auth()->user()->role === 'it'
                            ? 'Balas ke pelapor...'
                            : 'Ketik pesan ke TRIX Assistant...' }}"
                       autocomplete="off"
                       required>


                {{-- type="button" supaya tidak ditangkap script submit global --}}
                <button type="button"
                        class="btn btn-primary"
                        id="sendBtn">

                    <i class="bi bi-send" id="sendIcon"></i>

                </button>

            </form>

        </div>

    </div>

</div>


<style>

    /* 
     * LEBAR DIATUR: 1300px (pas, tidak terlalu penuh ke ujung layar)
     */
    .chat-container {
        max-width: 1300px;
        width: 100%;
        margin: 0 auto;
    }

    .chat-header-logo {
        width: 52px;
        height: 52px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        flex-shrink: 0;
    }

    .chat-header-logo img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    /* 
     * TINGGI CHAT: 650px 
     */
    .chat-messages {
        height: 650px;
        overflow-y: auto;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: .6rem .6rem 0 0;
    }

    .chat-bubble {
        max-width: 75%;
        margin-bottom: 1rem;
        display: flex;
        flex-direction: column;
    }

    .chat-bubble.mine {
        margin-left: auto;
        align-items: flex-end;
    }

    .chat-bubble.other {
        margin-right: auto;
        align-items: flex-start;
    }

    .chat-bubble .bubble-head {
        font-size: .75rem;
        color: #64748b;
        margin-bottom: .25rem;
        font-weight: 600;
    }

    .chat-bubble.mine .bubble-head {
        color: #0033a0;
    }

    .chat-bubble.it .bubble-head {
        color: #10b981;
    }

    .chat-bubble.ai .bubble-head {
        color: #3b82f6;
    }

    .chat-bubble .bubble-body {
        padding: .7rem 1rem;
        border-radius: 14px;
        line-height: 1.55;
        font-size: .9rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .chat-bubble .bubble-body p {
        margin: 0 0 .5rem 0;
    }

    .chat-bubble .bubble-body p:last-child {
        margin-bottom: 0;
    }

    .chat-bubble .bubble-body ul,
    .chat-bubble .bubble-body ol {
        margin: .25rem 0 .5rem 0;
        padding-left: 1.25rem;
    }

    .chat-bubble .bubble-body li {
        margin-bottom: .35rem;
    }

    .chat-bubble .bubble-body em {
        font-style: italic;
        font-size: .82rem;
        color: #64748b;
    }

    .chat-bubble .bubble-body strong {
        font-weight: 700;
    }

    .chat-bubble.mine .bubble-body {
        background: #0033a0;
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .chat-bubble.other .bubble-body {
        background: #fff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    .chat-bubble.ai .bubble-body {
        background: #eff6ff;
        color: #1e3a8a;
        border: 1px solid #bfdbfe;
        border-bottom-left-radius: 4px;
    }

    .chat-bubble.it .bubble-body {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-bottom-left-radius: 4px;
    }

    .chat-bubble .bubble-time {
        font-size: .7rem;
        color: #94a3b8;
        margin-top: .25rem;
    }

    .chat-input {
        padding: 1rem;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 .6rem .6rem;
    }

    .chat-input input {
        border-radius: 20px;
        padding: .6rem 1rem;
    }

    .chat-input button {
        border-radius: 20px;
        width: 45px;
        min-width: 45px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #sendBtn:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    /*
    |--------------------------------------------------------------------------
    | AI LOADING
    |--------------------------------------------------------------------------
    */

    .ai-loading {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 1rem;
        max-width: 75%;
    }

    .ai-loading .loading-avatar {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 50%;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: .85rem;
    }

    .ai-loading .loading-bubble {
        background: #eff6ff;
        color: #1e3a8a;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        border-bottom-left-radius: 4px;
        padding: .7rem 1rem;
        font-size: .9rem;
        line-height: 1.55;
    }

    .ai-loading .loading-dots {
        display: inline-flex;
        margin-left: 2px;
        min-width: 18px;
    }

    .ai-loading .loading-dots span {
        animation: aiDot 1.4s infinite;
        opacity: .3;
        font-weight: bold;
    }

    .ai-loading .loading-dots span:nth-child(2) {
        animation-delay: .2s;
    }

    .ai-loading .loading-dots span:nth-child(3) {
        animation-delay: .4s;
    }

    @keyframes aiDot {
        0%, 60%, 100% {
            opacity: .3;
        }

        30% {
            opacity: 1;
        }
    }

    .welcome-state {
        text-align: center;
        padding: 2rem 1rem;
    }

    .welcome-logo {
        width: 160px;
        height: 160px;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0, 51, 160, 0.15);
        animation: pulse 3s infinite;
    }

    .welcome-logo img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .welcome-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: .75rem;
        font-size: 1.15rem;
    }

    .welcome-text {
        color: #64748b;
        font-size: .9rem;
        line-height: 1.6;
        max-width: 500px;
        margin: 0 auto 1.5rem;
    }

    .welcome-tips {
        display: inline-flex;
        flex-direction: column;
        gap: .5rem;
        background: #fff;
        padding: 1rem 1.25rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        text-align: left;
        max-width: 420px;
    }

    .tip-item {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-size: .82rem;
        color: #475569;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @media (max-width: 768px) {
        .chat-messages {
            height: 450px;
            padding: 1rem;
        }

        .chat-bubble {
            max-width: 88%;
        }

        .ai-loading {
            max-width: 88%;
        }

        .welcome-logo {
            width: 120px;
            height: 120px;
            padding: 18px;
        }
    }

</style>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const chatMessages = document.getElementById('chatMessages');

    const messageInput = document.getElementById('messageInput');

    const sendBtn = document.getElementById('sendBtn');

    const sendIcon = document.getElementById('sendIcon');


    const csrfToken = document.querySelector(
        '#chatForm input[name="_token"]'
    ).value;


    const chatId = {{ $chat->id }};

    const logoUrl = @json(asset('images/logo-trix.png'));

    const aiQuotaText = document.getElementById('aiQuotaText');

    const aiQuotaBadge = document.getElementById('aiQuotaBadge');

    const isIT = {{ auth()->user()->role === 'it' ? 'true' : 'false' }};

    const pelaporName = @json($complaint->user->name ?? '-');

    const pcCode = @json($complaint->pc->kode_pc ?? '-');

    const catName = @json($complaint->category->nama_kategori ?? '-');


    let lastMessageId = 0;

    let sending = false;



    function escapeHtml(text) {

        const div = document.createElement('div');

        div.textContent = text ?? '';

        return div.innerHTML;

    }



    function formatMessage(text) {

        let html = escapeHtml(text ?? '');

        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

        html = html.replace(/_(.+?)_/g, '<em>$1</em>');

        const lines = html.split('\n');

        let output = '';

        let inList = null;


        lines.forEach(line => {

            const trimmed = line.trim();


            if (/^[-•]\s+/.test(trimmed)) {

                if (inList !== 'ul') {

                    if (inList === 'ol') {
                        output += '</ol>';
                    }

                    output += '<ul>';

                    inList = 'ul';

                }

                output += '<li>' +
                    trimmed.replace(/^[-•]\s+/, '') +
                    '</li>';


            } else if (/^\d+\.\s+/.test(trimmed)) {

                if (inList !== 'ol') {

                    if (inList === 'ul') {
                        output += '</ul>';
                    }

                    output += '<ol>';

                    inList = 'ol';

                }

                output += '<li>' +
                    trimmed.replace(/^\d+\.\s+/, '') +
                    '</li>';


            } else if (trimmed === '') {

                if (inList) {

                    output += inList === 'ol'
                        ? '</ol>'
                        : '</ul>';

                    inList = null;

                }


            } else {

                if (inList) {

                    output += inList === 'ol'
                        ? '</ol>'
                        : '</ul>';

                    inList = null;

                }

                output += '<p>' + trimmed + '</p>';

            }

        });


        if (inList) {

            output += inList === 'ol'
                ? '</ol>'
                : '</ul>';

        }

        return output;

    }



    function renderWelcomeState() {

        if (isIT) {

            return `
                <div class="welcome-state">
                    <div class="welcome-logo">
                        <img src="${logoUrl}" alt="TRIX">
                    </div>
                    <h5 class="welcome-title">
                        Chat dengan ${escapeHtml(pelaporName)}
                    </h5>
                    <p class="welcome-text">
                        Ini percakapan antara pelapor dan TRIX Assistant.
                        Anda bisa ikut membantu kapan saja.
                    </p>
                    <div class="welcome-tips">
                        <div class="tip-item">
                            <i class="bi bi-person-badge text-primary"></i>
                            <span>
                                Pelapor:
                                <strong>${escapeHtml(pelaporName)}</strong>
                            </span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-pc-display text-info"></i>
                            <span>
                                Perangkat:
                                <strong>${escapeHtml(pcCode)}</strong>
                            </span>
                        </div>
                        <div class="tip-item">
                            <i class="bi bi-tag text-warning"></i>
                            <span>
                                Kategori:
                                <strong>${escapeHtml(catName)}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            `;

        }


        return `
            <div class="welcome-state">
                <div class="welcome-logo">
                    <img src="${logoUrl}" alt="TRIX">
                </div>
                <h5 class="welcome-title">
                    Halo! Saya TRIX Assistant 👋
                </h5>
                <p class="welcome-text">
                    Saya siap membantu menyelesaikan kendala IT Anda.
                    Ceritakan masalahnya, saya akan bantu.
                </p>
                <div class="welcome-tips">
                    <div class="tip-item">
                        <i class="bi bi-lightbulb text-warning"></i>
                        <span>Jelaskan kendala sedetail mungkin</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-chat-dots text-primary"></i>
                        <span>Sebutkan pesan error jika ada</span>
                    </div>
                    <div class="tip-item">
                        <i class="bi bi-info-circle text-info"></i>
                        <span>Kuota AI 50x balasan per hari</span>
                    </div>
                </div>
            </div>
        `;

    }



    function renderMessages(messages) {

        if (!messages || messages.length === 0) {

            chatMessages.innerHTML = renderWelcomeState();

            return;

        }


        let html = '';

        let lastDate = '';


        messages.forEach(m => {

            if (m.date !== lastDate) {

                html += `
                    <div class="text-center my-3">
                        <span class="badge bg-secondary bg-opacity-50">
                            ${escapeHtml(m.date)}
                        </span>
                    </div>
                `;

                lastDate = m.date;

            }


            const isMine = !!m.is_mine;

            const isAi = m.sender_type === 'ai';

            const isItSender = m.sender_type === 'it';


            let cls = isMine ? 'mine' : 'other';

            if (isAi) {
                cls = 'other ai';
            }

            if (isItSender && !isMine) {
                cls = 'other it';
            }


            let bodyContent;

            if (isAi) {

                bodyContent = formatMessage(m.message);

            } else {

                bodyContent = escapeHtml(m.message).replace(/\n/g, '<br>');

            }


            html += `
                <div class="chat-bubble ${cls}">
                    <div class="bubble-head">
                        ${escapeHtml(m.sender_name)}
                    </div>
                    <div class="bubble-body">
                        ${bodyContent}
                    </div>
                    <div class="bubble-time">
                        ${escapeHtml(m.time)}
                    </div>
                </div>
            `;

        });


        chatMessages.innerHTML = html;

        chatMessages.scrollTop = chatMessages.scrollHeight;

    }



    function updateQuotaBadge(quota) {

        if (!quota || !aiQuotaText || !aiQuotaBadge) {

            return;

        }

        aiQuotaText.textContent = quota.remaining;

        if (quota.remaining === 0) {

            aiQuotaBadge.className = 'badge bg-danger';

        } else if (quota.remaining <= 10) {

            aiQuotaBadge.className = 'badge bg-warning text-dark';

        } else {

            aiQuotaBadge.className = 'badge bg-info';

        }

    }



    function showAiLoading() {

        if (document.getElementById('aiLoadingMessage')) {

            return;

        }


        const div = document.createElement('div');

        div.id = 'aiLoadingMessage';

        div.className = 'ai-loading';

        div.innerHTML = `
            <div class="loading-avatar">
                <i class="bi bi-robot"></i>
            </div>
            <div class="loading-bubble">
                <span>TRIX Assistant sedang membalas</span>
                <span class="loading-dots">
                    <span>.</span>
                    <span>.</span>
                    <span>.</span>
                </span>
            </div>
        `;


        chatMessages.appendChild(div);

        chatMessages.scrollTop = chatMessages.scrollHeight;

    }



    function hideAiLoading() {

        const loading = document.getElementById('aiLoadingMessage');

        if (loading) {

            loading.remove();

        }

    }



    async function loadMessages() {

        try {

            const res = await fetch(`/chat/${chatId}/fetch`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-store'
            });


            if (!res.ok) {

                throw new Error('Server error: ' + res.status);

            }


            const data = await res.json();

            const messages = Array.isArray(data.messages) ? data.messages : [];

            const newLastId = messages.length ? messages[messages.length - 1].id : 0;


            if (newLastId !== lastMessageId) {

                lastMessageId = newLastId;

                renderMessages(messages);

            }


            if (!isIT) {

                const hasAiReply = messages.some(message => message.sender_type === 'ai');

                if (hasAiReply) {

                    hideAiLoading();

                }

            }


            if (data.ai_quota) {

                updateQuotaBadge(data.ai_quota);

            }


        } catch (err) {

            console.error('Load messages error:', err);

        }

    }



    function appendLocalMessage(text) {

        const welcome = chatMessages.querySelector('.welcome-state');

        if (welcome) {

            welcome.remove();

        }


        const div = document.createElement('div');

        div.className = 'chat-bubble mine';


        const now = new Date();

        const time = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });


        div.innerHTML = `
            <div class="bubble-head">Anda</div>
            <div class="bubble-body">${escapeHtml(text)}</div>
            <div class="bubble-time">${time}</div>
        `;


        chatMessages.appendChild(div);

        chatMessages.scrollTop = chatMessages.scrollHeight;

    }



    function resetSendButton() {

        sending = false;

        sendBtn.disabled = false;

        sendBtn.dataset.sending = 'false';

        sendIcon.className = 'bi bi-send';

        messageInput.disabled = false;

        messageInput.focus();

    }



    async function sendMessage() {

        if (sending) {

            return;

        }


        const msg = messageInput.value.trim();

        if (!msg) {

            return;

        }


        sending = true;

        sendBtn.disabled = true;

        sendBtn.dataset.sending = 'true';

        sendIcon.className = 'bi bi-send';


        appendLocalMessage(msg);


        if (!isIT) {

            showAiLoading();

        }


        messageInput.value = '';


        try {

            const controller = new AbortController();

            const timeout = setTimeout(() => {
                controller.abort();
            }, 30000);


            const res = await fetch(`/chat/${chatId}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: msg
                }),
                signal: controller.signal
            });


            clearTimeout(timeout);


            if (!res.ok) {

                let errorMessage = 'Server error: ' + res.status;

                try {

                    const errorData = await res.json();

                    if (errorData.message) {

                        errorMessage = errorData.message;

                    }

                } catch (_) {}


                throw new Error(errorMessage);

            }


            setTimeout(loadMessages, 300);
            setTimeout(loadMessages, 1000);
            setTimeout(loadMessages, 2000);
            setTimeout(loadMessages, 4000);
            setTimeout(loadMessages, 7000);
            setTimeout(loadMessages, 10000);


        } catch (error) {

            console.error('Send error:', error);

            setTimeout(loadMessages, 1000);
            setTimeout(loadMessages, 3000);
            setTimeout(loadMessages, 6000);

            if (error.name !== 'AbortError') {

                console.error('Pesan gagal dikirim:', error.message);

            }


        } finally {

            resetSendButton();

        }

    }



    sendBtn.addEventListener('click', function (e) {

        e.preventDefault();

        sendMessage();

    });



    messageInput.addEventListener('keydown', function (e) {

        if (e.key === 'Enter' && !e.shiftKey) {

            e.preventDefault();

            sendMessage();

        }

    });



    loadMessages();

    setInterval(loadMessages, 3000);


});

</script>

@endpush