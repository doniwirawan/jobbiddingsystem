<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredBids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-bids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find all bids where the deadline has passed and they haven't been accepted or rejected
        $expiredBids = Bid::where('is_accepted', null)
                        ->where('deadline', '<', now())
                        ->get();

        // Cancel all expired bids
        foreach ($expiredBids as $bid) {
            $bid->update(['is_accepted' => false]); // Mark as rejected due to expiration
            // Optionally notify the user that their bid has expired
        }
    }

}
