<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use Illuminate\Console\Command;

/**
 * Removes portfolio entries that make a claim the business cannot support.
 *
 * The catalogue was built by mapping a list of URLs to "Client: <domain>", and the
 * list included kyliecosmetics.com, gymshark.com, colourpop.com and allbirds.com —
 * companies with their own in-house teams. It also included a canva.com/design/…/edit
 * link, which is an editor URL rather than a website, and a wixsite.com URL still
 * carrying ?utm_source=chatgpt.com, which is what a link looks like when it has been
 * pasted out of a chatbot answer.
 *
 * The seeder no longer produces these, but the seeder is not run on deploy — the
 * catalogue is editable from the admin, so reseeding would discard real edits. This
 * command deletes only the rows matching the list below, which makes it safe to run
 * on every deploy: it is a guard that keeps them from coming back, not a reseed.
 */
class PrunePortfolioClaims extends Command
{
    protected $signature = 'portfolio:prune-claims {--dry-run : List what would be removed without deleting}';

    protected $description = 'Remove portfolio entries whose client claim cannot be supported';

    /** Matched against project_url and client_name, case-insensitively. */
    private const BANNED = [
        'kyliecosmetics.com',
        'gymshark.com',
        'colourpop.com',
        'allbirds.com',
        'loxclubapp.com',
        '//www.butter.us',
        '//butter.us',
        'adaline.ai',
        'canva.com/design',
        // Specific hosts, not the platforms: 'wixsite.com' or 'webflow.io' on their
        // own would also delete a genuine Wix or Webflow project added later from the
        // admin, and the model has no soft deletes to recover it from.
        'gannonchess.wixsite.com',
        'risdcareers.wixsite.com',
        'imo2017.webflow.io',
        'uwdesign2017.webflow.io',
    ];

    public function handle(): int
    {
        $matches = Portfolio::query()
            ->where(function ($query) {
                foreach (self::BANNED as $needle) {
                    $query->orWhere('project_url', 'like', '%' . $needle . '%')
                        ->orWhere('client_name', 'like', '%' . $needle . '%');
                }
            })
            ->get();

        if ($matches->isEmpty()) {
            $this->info('portfolio:prune-claims — nothing to remove.');

            return self::SUCCESS;
        }

        foreach ($matches as $portfolio) {
            $this->line(sprintf('  %s  %s', $portfolio->slug, $portfolio->project_url));
        }

        if ($this->option('dry-run')) {
            $this->warn(sprintf('Dry run: %d entr%s would be removed.', $matches->count(), $matches->count() === 1 ? 'y' : 'ies'));

            return self::SUCCESS;
        }

        $removed = Portfolio::query()->whereIn('id', $matches->pluck('id'))->delete();
        $this->info(sprintf('Removed %d portfolio entr%s.', $removed, $removed === 1 ? 'y' : 'ies'));

        return self::SUCCESS;
    }
}
