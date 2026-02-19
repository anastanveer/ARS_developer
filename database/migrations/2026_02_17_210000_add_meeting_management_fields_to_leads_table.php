<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('meeting_token', 64)->nullable()->unique()->after('meeting_slot');
            $table->string('meeting_timezone', 80)->nullable()->after('meeting_token');
            $table->date('meeting_previous_date')->nullable()->after('meeting_timezone');
            $table->string('meeting_previous_slot', 120)->nullable()->after('meeting_previous_date');
            $table->timestamp('meeting_confirmed_at')->nullable()->after('meeting_previous_slot');
            $table->timestamp('meeting_rescheduled_at')->nullable()->after('meeting_confirmed_at');
            $table->timestamp('meeting_cancelled_at')->nullable()->after('meeting_rescheduled_at');
            $table->timestamp('meeting_reminder_24h_sent_at')->nullable()->after('meeting_cancelled_at');
            $table->timestamp('meeting_reminder_2h_sent_at')->nullable()->after('meeting_reminder_24h_sent_at');
        });

        DB::table('leads')
            ->where('type', 'meeting')
            ->whereNull('meeting_token')
            ->orderBy('id')
            ->select('id')
            ->chunk(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('leads')
                        ->where('id', $row->id)
                        ->update([
                            'meeting_token' => (string) Str::uuid(),
                            'meeting_timezone' => DB::raw("COALESCE(meeting_timezone, 'Europe/London')"),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'meeting_token',
                'meeting_timezone',
                'meeting_previous_date',
                'meeting_previous_slot',
                'meeting_confirmed_at',
                'meeting_rescheduled_at',
                'meeting_cancelled_at',
                'meeting_reminder_24h_sent_at',
                'meeting_reminder_2h_sent_at',
            ]);
        });
    }
};
