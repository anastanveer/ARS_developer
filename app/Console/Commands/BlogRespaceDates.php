<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Re-space blog post published_at dates so posts go out evenly — by default
 * 2 per week (Tuesday + Friday), in their existing chronological order.
 *
 * Fixes clustering (e.g. 3 posts on one day) and gaps between scheduled batches.
 *
 * Preview first:   php artisan blog:respace --dry
 * Apply:           php artisan blog:respace
 * Custom cadence:  php artisan blog:respace --days=1,4 --time=09:00 --start=2026-06-22
 */
class BlogRespaceDates extends Command
{
    protected $signature = 'blog:respace
        {--days=2,5 : Weekday numbers per week, 1=Mon..7=Sun (default Tue,Fri)}
        {--time=10:00 : Time of day for each post (HH:MM)}
        {--start= : Anchor Monday (Y-m-d). Defaults to the Monday of the earliest post}
        {--only-published : Re-space only already-published posts, leave scheduled ones untouched}
        {--dry : Preview the new schedule without saving}';

    protected $description = 'Evenly re-space blog published_at dates (default 2/week: Tue & Fri)';

    public function handle(): int
    {
        $days = collect(explode(',', (string) $this->option('days')))
            ->map(fn ($d) => (int) trim($d))
            ->filter(fn ($d) => $d >= 1 && $d <= 7)
            ->unique()
            ->sort()
            ->values();

        if ($days->isEmpty()) {
            $this->error('No valid --days provided (use 1..7, e.g. --days=2,5).');
            return self::FAILURE;
        }

        [$hh, $mm] = array_pad(explode(':', (string) $this->option('time')), 2, '0');
        $hh = (int) $hh;
        $mm = (int) $mm;

        $query = BlogPost::query()
            ->orderByRaw('published_at IS NULL')      // nulls last
            ->orderBy('published_at')
            ->orderBy('id');

        if ($this->option('only-published')) {
            $query->where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            $this->warn('No blog posts found to re-space.');
            return self::SUCCESS;
        }

        // Anchor = Monday of the chosen start week (or of the earliest post).
        if ($this->option('start')) {
            $anchor = Carbon::parse($this->option('start'))->startOfWeek(Carbon::MONDAY);
        } else {
            $firstDated = $posts->first(fn ($p) => $p->published_at !== null);
            $anchor = ($firstDated?->published_at ?? now())->copy()->startOfWeek(Carbon::MONDAY);
        }

        // Build enough evenly-spaced slots.
        $slots = [];
        $weeksNeeded = (int) ceil($posts->count() / $days->count()) + 1;
        for ($w = 0; $w < $weeksNeeded; $w++) {
            foreach ($days as $dow) {
                $slots[] = $anchor->copy()->addWeeks($w)->addDays($dow - 1)->setTime($hh, $mm, 0);
            }
        }

        $this->line('');
        $this->info(sprintf(
            'Re-spacing %d posts · %d/week on %s at %02d:%02d · anchor %s',
            $posts->count(),
            $days->count(),
            $days->map(fn ($d) => Carbon::create()->startOfWeek(Carbon::MONDAY)->addDays($d - 1)->format('D'))->implode(' & '),
            $hh,
            $mm,
            $anchor->toDateString()
        ));
        $this->line('');

        $rows = [];
        $updates = [];
        foreach ($posts as $i => $post) {
            $new = $slots[$i];
            $rows[] = [
                $post->published_at?->format('Y-m-d') ?? '—',
                $new->format('Y-m-d (D)'),
                $new->isFuture() ? 'scheduled' : 'live',
                mb_substr($post->title, 0, 46),
            ];
            $updates[] = ['id' => $post->id, 'published_at' => $new];
        }

        $this->table(['Old date', 'New date', 'State', 'Title'], $rows);

        if ($this->option('dry')) {
            $this->warn('DRY RUN — nothing saved. Re-run without --dry to apply.');
            return self::SUCCESS;
        }

        DB::transaction(function () use ($updates) {
            foreach ($updates as $u) {
                BlogPost::whereKey($u['id'])->update(['published_at' => $u['published_at']]);
            }
        });

        $this->info('✅ Done. '.count($updates).' posts re-spaced.');
        $this->line('Remember to clear caches if used: php artisan cache:clear');
        return self::SUCCESS;
    }
}
