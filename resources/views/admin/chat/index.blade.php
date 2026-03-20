@extends('admin.layout', ['title' => 'Chat Inbox'])

@php
    $selected = $selectedConversation;
@endphp

@section('content')
<div class="top">
    <h1 style="margin:0">Website Chat Inbox</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a class="btn {{ $currentStatus === 'open' ? '' : 'gray' }}" href="{{ route('admin.chat.index', ['status' => 'open']) }}">Open Chats</a>
        <a class="btn {{ $currentStatus === 'closed' ? '' : 'gray' }}" href="{{ route('admin.chat.index', ['status' => 'closed']) }}">Closed Chats</a>
    </div>
</div>

<div class="surface">
    <div class="chat-admin">
        <div class="chat-admin__sidebar card">
            <div class="chat-admin__sidebar-head">
                <strong>Conversations</strong>
                <span class="muted">{{ $conversations->total() }} total</span>
            </div>
            <div class="chat-admin__list">
                @forelse($conversations as $conversation)
                    <a
                        href="{{ route('admin.chat.index', ['status' => $currentStatus, 'conversation' => $conversation->id]) }}"
                        class="chat-admin__item {{ $selected && $selected->id === $conversation->id ? 'is-active' : '' }}"
                    >
                        <div class="chat-admin__item-top">
                            <strong>{{ $conversation->name ?: 'Website Visitor' }}</strong>
                            @if(($conversation->unread_count ?? 0) > 0)
                                <span class="pill">{{ $conversation->unread_count }} new</span>
                            @endif
                        </div>
                        <div class="muted" style="font-size:13px;">{{ $conversation->email ?: ($conversation->phone ?: 'No contact detail') }}</div>
                        <div class="muted" style="font-size:12px;margin-top:4px;">{{ optional($conversation->last_visitor_message_at ?? $conversation->updated_at)->diffForHumans() }}</div>
                    </a>
                @empty
                    <div class="muted">No chat conversations yet.</div>
                @endforelse
            </div>
            <div style="margin-top:14px;">{{ $conversations->links() }}</div>
        </div>

        <div class="chat-admin__main card">
            @if($selected)
                <div class="chat-admin__head">
                    <div>
                        <h2 style="margin:0 0 6px;">{{ $selected->name ?: 'Website Visitor' }}</h2>
                        <div class="muted">
                            {{ $selected->email ?: 'No email' }}
                            @if($selected->phone)
                                · {{ $selected->phone }}
                            @endif
                            · {{ strtoupper($selected->preferred_channel ?: 'chat') }}
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;">
                        @if($selected->phone)
                            @php
                                $waDigits = preg_replace('/\D+/', '', (string) $selected->phone);
                                $waText = rawurlencode('Hi ' . ($selected->name ?: '') . ', regarding your website chat with ARSDeveloper.');
                            @endphp
                            <a class="btn gray" href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" target="_blank" rel="noopener">Open WhatsApp</a>
                        @endif
                        @if($selected->email)
                            <a class="btn gray" href="mailto:{{ $selected->email }}">Send Email</a>
                        @endif
                        <form method="post" action="{{ route('admin.chat.status', $selected) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $selected->status === 'closed' ? 'open' : 'closed' }}">
                            <button class="btn {{ $selected->status === 'closed' ? '' : 'gray' }}" type="submit">
                                {{ $selected->status === 'closed' ? 'Reopen Chat' : 'Close Chat' }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="chat-admin__meta">
                    <div class="stat">
                        <b>Status</b>
                        <span style="font-size:18px;">{{ ucfirst($selected->status) }}</span>
                    </div>
                    <div class="stat">
                        <b>Source Page</b>
                        <span style="font-size:18px;">{{ $selected->source_page ?: '-' }}</span>
                    </div>
                </div>

                <div class="chat-admin__messages">
                    @foreach($selected->messages as $message)
                        <div class="chat-admin__bubble chat-admin__bubble--{{ $message->sender_type }}">
                            <div class="chat-admin__bubble-name">
                                {{ $message->sender_name ?: ucfirst($message->sender_type) }}
                                <span>{{ optional($message->created_at)->format('d M Y, h:i A') }}</span>
                            </div>
                            @if($message->body)
                                <div>{{ $message->body }}</div>
                            @endif
                            @if($message->attachment_path)
                                <div style="margin-top:10px;">
                                    <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank" rel="noopener">
                                        <img src="{{ asset('storage/' . $message->attachment_path) }}" alt="Attachment" style="max-width:220px;border-radius:12px;display:block;">
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <form method="post" action="{{ route('admin.chat.reply', $selected) }}" class="chat-admin__reply" data-chat-admin-reply data-typing-url="{{ route('admin.chat.typing', $selected) }}" data-csrf="{{ csrf_token() }}">
                    @csrf
                    <textarea name="body" rows="4" placeholder="Type your reply for this client..." required></textarea>
                    <button class="btn" type="submit">Send Reply</button>
                </form>
            @else
                <div class="muted">Select a conversation from the left to open the thread.</div>
            @endif
        </div>
    </div>
</div>

<style>
    .chat-admin{display:grid;grid-template-columns:320px minmax(0,1fr);gap:16px}
    .chat-admin__sidebar-head,.chat-admin__head{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:14px}
    .chat-admin__list{display:grid;gap:10px}
    .chat-admin__item{display:block;padding:12px;border:1px solid #e1eafa;border-radius:14px;text-decoration:none;color:#173257;background:#fff}
    .chat-admin__item.is-active{border-color:#7db4f7;background:#f6fbff}
    .chat-admin__item-top{display:flex;justify-content:space-between;gap:10px;align-items:center;margin-bottom:6px}
    .chat-admin__meta{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:16px}
    .chat-admin__messages{display:grid;gap:12px;max-height:540px;overflow:auto;padding-right:4px}
    .chat-admin__bubble{max-width:86%;padding:14px 16px;border-radius:18px;line-height:1.7}
    .chat-admin__bubble--visitor{background:#edf5ff;border:1px solid #d5e5fb}
    .chat-admin__bubble--admin{background:#153d7a;color:#fff;margin-left:auto}
    .chat-admin__bubble--system{background:#f7f9fc;border:1px dashed #d8e2f1}
    .chat-admin__bubble-name{font-weight:700;font-size:13px;margin-bottom:7px}
    .chat-admin__bubble-name span{display:block;font-weight:400;color:inherit;opacity:.7;margin-top:3px}
    .chat-admin__reply{display:grid;gap:10px;margin-top:16px}
    .chat-admin__reply textarea{width:100%;border:1px solid #d7e4f8;border-radius:16px;padding:14px 16px;font:inherit;min-height:120px}
    @media (max-width: 980px){
        .chat-admin{grid-template-columns:1fr}
        .chat-admin__meta{grid-template-columns:1fr}
        .chat-admin__bubble{max-width:100%}
    }
</style>
@if($selected)
<script>
(function () {
    var form = document.querySelector('[data-chat-admin-reply]');
    if (!form) return;
    var textarea = form.querySelector('textarea[name=body]');
    var timer = null;
    function pingTyping() {
        fetch(form.getAttribute('data-typing-url'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.getAttribute('data-csrf'),
                'Accept': 'application/json'
            }
        }).catch(function () {});
    }
    textarea.addEventListener('input', function () {
        if (timer) { clearTimeout(timer); }
        pingTyping();
        timer = setTimeout(function () {}, 2500);
    });
    textarea.addEventListener('focus', pingTyping);
})();
</script>
@endif
@endsection
