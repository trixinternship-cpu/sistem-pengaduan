<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Complaint;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService)
    {
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'it') {
            $chats = Chat::whereHas('complaint')->with(['complaint.pc', 'complaint.category', 'pelapor', 'latestMessage'])
                ->orderByDesc('last_message_at')
                ->orderByDesc('created_at')
                ->paginate(20);
        } else {
            $chats = Chat::whereHas('complaint')->with(['complaint.pc', 'complaint.category', 'teknisi', 'latestMessage'])
                ->where('pelapor_id', $user->id)
                ->orderByDesc('last_message_at')
                ->paginate(20);
        }

        return view('chat.index', compact('chats'));
    }

    public function show(Complaint $complaint)
    {
        $user = Auth::user();

        if ($user->role === 'pelapor' && $complaint->user_id !== $user->id) {
            abort(403);
        }

        $chat = $this->chatService->ensureChatForComplaint($complaint);
        $chat->load(['messages.user', 'complaint.pc', 'complaint.category']);
        $this->chatService->markAsRead($chat, $user->id);

        return view('chat.show', compact('chat', 'complaint'));
    }

    public function fetch(Chat $chat)
    {
        $user = Auth::user();

        if ($user->role === 'pelapor' && $chat->pelapor_id !== $user->id) {
            abort(403);
        }

        abort_if(!$chat->complaint, 404);

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'sender_type' => $m->sender_type,
                    'sender_name' => $m->sender_label,
                    'message' => $m->message,
                    'is_mine' => $m->user_id === Auth::id(),
                    'time' => $m->created_at->timezone(config('app.timezone'))->format('H:i'),
                    'date' => $m->created_at->timezone(config('app.timezone'))->format('d M Y'),
                ];
            });

        $pelapor = User::find($chat->pelapor_id);
        $aiQuota = null;
        if ($pelapor) {
            $aiQuota = [
                'used' => $pelapor->ai_chat_count,
                'limit' => User::AI_CHAT_LIMIT,
                'remaining' => $pelapor->remainingAiChat(),
            ];
        }

        return response()->json([
            'messages' => $messages,
            'ai_quota' => $aiQuota,
        ]);
    }

    public function send(Request $request, Chat $chat)
    {
        $user = Auth::user();

        if ($user->role === 'pelapor' && $chat->pelapor_id !== $user->id) {
            abort(403);
        }

        abort_if(!$chat->complaint, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $this->chatService->sendMessage($chat, $user, $data['message']);

        if ($user->role === 'pelapor') {
            if ($user->canUseAiChat()) {
                $this->chatService->aiReply($chat, $data['message']);
            }
        }

        return response()->json(['success' => true]);
    }

    public function resetQuota(User $user)
    {
        if (Auth::user()->role !== 'it') {
            abort(403);
        }

        $user->resetAiChat();

        return response()->json(['success' => true]);
    }
}