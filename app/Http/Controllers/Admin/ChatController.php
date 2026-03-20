<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ChatClientReplyMail;
use App\Models\ChatConversation;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(private readonly WhatsAppService $whatsAppService)
    {
    }

    public function index(Request $request): View
    {
        $status = trim((string) $request->query('status', 'open'));

        $conversations = ChatConversation::query()
            ->withCount([
                'unreadVisitorMessages as unread_count',
                'messages as message_count',
            ])
            ->when(in_array($status, ['open', 'closed'], true), fn ($query) => $query->where('status', $status))
            ->orderByRaw('COALESCE(last_visitor_message_at, last_admin_message_at, updated_at) DESC')
            ->paginate(20)
            ->withQueryString();

        $selected = null;
        $selectedId = (int) $request->query('conversation', 0);

        if ($selectedId > 0) {
            $selected = ChatConversation::query()->with('messages')->find($selectedId);
        } elseif ($conversations->count() > 0) {
            $selected = ChatConversation::query()->with('messages')->find($conversations->first()->id);
        }

        if ($selected) {
            $selected->messages()
                ->where('sender_type', 'visitor')
                ->where('is_read_by_admin', false)
                ->update(['is_read_by_admin' => true]);
            $selected->load('messages');
        }

        return view('admin.chat.index', [
            'conversations' => $conversations,
            'selectedConversation' => $selected,
            'currentStatus' => $status,
        ]);
    }

    public function reply(Request $request, ChatConversation $conversation): RedirectResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $body = trim((string) $request->input('body'));
        $reply = $conversation->messages()->create([
            'sender_type' => 'admin',
            'sender_name' => session('admin_name', session('admin_email', 'Admin Team')),
            'body' => $body,
            'channel' => $conversation->preferred_channel ?: 'chat',
            'is_read_by_admin' => true,
            'is_read_by_visitor' => false,
        ]);

        $conversation->forceFill([
            'status' => 'open',
            'closed_at' => null,
            'last_admin_message_at' => now(),
            'admin_typing_at' => null,
        ])->save();

        if ($conversation->email) {
            try {
                Mail::to($conversation->email)->send(new ChatClientReplyMail($conversation, $reply));
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        if ($conversation->preferred_channel === 'whatsapp' && $conversation->phone) {
            $result = $this->whatsAppService->sendTextMessage($conversation->phone, $body);
            $reply->forceFill([
                'channel' => 'whatsapp',
                'external_message_id' => $result['message_id'] ?? null,
                'message_status' => ($result['success'] ?? false) ? 'sent' : 'failed',
                'raw_payload' => $result['data'] ?? ['error' => $result['error'] ?? null],
            ])->save();
        }

        return redirect()
            ->route('admin.chat.index', ['conversation' => $conversation->id])
            ->with('status', 'Reply sent in chat inbox.');
    }

    public function typing(ChatConversation $conversation): JsonResponse
    {
        $conversation->forceFill([
            'admin_typing_at' => now(),
        ])->save();

        return response()->json(['success' => true]);
    }

    public function status(Request $request, ChatConversation $conversation): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $status = (string) $request->input('status');

        $conversation->forceFill([
            'status' => $status,
            'closed_at' => $status === 'closed' ? now() : null,
            'admin_typing_at' => null,
        ])->save();

        return redirect()
            ->route('admin.chat.index', ['conversation' => $conversation->id, 'status' => $status])
            ->with('status', 'Chat status updated.');
    }
}
