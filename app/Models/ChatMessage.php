<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_conversation_id',
        'sender_type',
        'sender_name',
        'body',
        'channel',
        'external_message_id',
        'message_status',
        'raw_payload',
        'attachment_path',
        'attachment_name',
        'is_read_by_admin',
        'is_read_by_visitor',
    ];

    protected $casts = [
        'is_read_by_admin' => 'boolean',
        'is_read_by_visitor' => 'boolean',
        'raw_payload' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'chat_conversation_id');
    }
}
