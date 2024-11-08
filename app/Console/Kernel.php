<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ExecuteScheduledTransfers;
use App\Models\Transactions;
use App\Jobs\TransferPlanifieJob;
use Carbon\Carbon;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // app/Console/Kernel.php

    protected $commands = [
        ExecuteScheduledTransfers::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $scheduledTransactions = Transactions::where('status', 'planifie')
                ->get();
    
            foreach ($scheduledTransactions as $transaction) {
            TransferPlanifieJob::dispatch($transaction->id);
            }
        })->everyMinute(); // Vous pouvez ajuster la fréquence d'exécution
    }
    

}
