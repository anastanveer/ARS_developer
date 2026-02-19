<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public const STANDARD_STATUSES = [
        'new',
        'in_progress',
        'contacted',
        'converted',
        'closed',
    ];

    public const MEETING_PIPELINE_STATUSES = [
        'new',
        'meeting_confirmed',
        'meeting_rescheduled',
        'meeting_completed',
        'no_show',
        'cancelled',
        'contacted',
        'in_progress',
    ];

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'meeting_date',
        'meeting_slot',
        'meeting_token',
        'meeting_timezone',
        'meeting_previous_date',
        'meeting_previous_slot',
        'meeting_confirmed_at',
        'meeting_rescheduled_at',
        'meeting_cancelled_at',
        'meeting_reminder_24h_sent_at',
        'meeting_reminder_2h_sent_at',
        'project_type',
        'budget_range',
        'coupon_code',
        'coupon_discount',
        'quote_final_preview',
        'coupon_validated',
        'status',
        'is_blocked',
        'blocked_reason',
        'last_followup_at',
        'ip',
        'country',
        'submitted_from',
        'user_agent',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_previous_date' => 'date',
        'is_blocked' => 'boolean',
        'coupon_discount' => 'decimal:2',
        'quote_final_preview' => 'decimal:2',
        'coupon_validated' => 'boolean',
        'last_followup_at' => 'datetime',
        'meeting_confirmed_at' => 'datetime',
        'meeting_rescheduled_at' => 'datetime',
        'meeting_cancelled_at' => 'datetime',
        'meeting_reminder_24h_sent_at' => 'datetime',
        'meeting_reminder_2h_sent_at' => 'datetime',
    ];

    public static function statusOptions(): array
    {
        return array_values(array_unique(array_merge(self::STANDARD_STATUSES, self::MEETING_PIPELINE_STATUSES)));
    }

    public static function meetingOpenStatuses(): array
    {
        return [
            'new',
            'in_progress',
            'contacted',
            'meeting_confirmed',
            'meeting_rescheduled',
        ];
    }

    public function statusChoices(): array
    {
        return $this->type === 'meeting'
            ? self::MEETING_PIPELINE_STATUSES
            : self::STANDARD_STATUSES;
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }
}
