<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Fix ONLY duplicate publish dates: when 2+ posts share a day, the earliest
 * keeps the date and the extras move to the nearest free day. Every other
 * post is left exactly as-is (so a good schedule like Aug Mon/Wed is untouched).
 *
 * Preview:  php artisan blog:decluster --dry
 * Apply:    php artisan blog:decluster
 */
class BlogDecluster extends Command
{
    protected $signature = 'blog:decluster {--dry : Preview changes without saving}';
    protected $description = 'Ensure no two posts share the same publish date; move only the duplicates to the nearest free day';

    public function handle(): int
    {
        $posts = BlogPost::orderBy('published_at')->orderBy('id')->get();

        $occupied = [];
        $moves = [];

        foreach ($posts as $post) {
            if (!$post->published_at) {
                continue;
            }
            $day = $post->published_at->format('Y-m-d');

            if (!isset($occupied[$day])) {
                $occupied[$day] = true; // first post on this day keeps it
                continue;
            }

            // Duplicate day — find the nearest free day (outwards: +1,-1,+2,-2 ...)
            $base = $post->published_at->copy();
            $newDate = null;
            for ($step = 1; $step <= 180 && !$newDate; $step++) {
                foreach ([$step, -$step] as $delta) {
                    $cand = $base->copy()->addDays($delta);
                    if (!isset($occupied[$cand->format('Y-m-d')])) {
                        $newDate = $cand;
                        break;
                    }
                }
            }
            if (!$newDate) {
                continue;
            }

            $occupied[$newDate->format('Y-m-d')] = true;
            $moves[] = [
                'post' => $post,
                'from' => $day,
                'to' => $newDate,
            ];
        }

        if (empty($moves)) {
            $this->info('No duplicate dates found — nothing to fix. 🎉');
            return self::SUCCESS;
        }

        $this->table(
            ['From (clashed)', 'To (free)', 'Title'],
            array_map(fn ($m) => [
                $m['from'],
                $m['to']->format('Y-m-d (D)'),
                mb_substr($m['post']->title, 0, 50),
            ], $moves),
        );

        if ($this->option('dry')) {
            $this->warn('DRY RUN — nothing saved. Re-run without --dry to apply.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($moves) {
            foreach ($moves as $m) {
                $orig = $m['post']->published_at;
                $newDt = $m['to']->copy()->setTime($orig->hour, $orig->minute, $orig->second);
                $m['post']->forceFill(['published_at' => $newDt])->save();
            }
        });

        $this->info('✅ De-clustered '.count($moves).' post(s). No date has more than one post now.');
        return self::SUCCESS;
    }
}
