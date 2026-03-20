<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_token',
        'name',
        'email',
        'phone',
        'preferred_channel',
        'status',
        'source_page',
        'visitor_ip',
        'user_agent',
        'last_visitor_message_at',
        'last_admin_message_at',
        'admin_typing_at',
        'closed_at',
    ];

    protected $casts = [
        'last_visitor_message_at' => 'datetime',
        'last_admin_message_at' => 'datetime',
        'admin_typing_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('id');
    }

    public function unreadVisitorMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)
            ->where('sender_type', 'visitor')
            ->where('is_read_by_admin', false);
    }
}
