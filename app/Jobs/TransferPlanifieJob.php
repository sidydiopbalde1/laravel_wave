<?php

namespace App\Jobs;

use App\Models\Transactions;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class TransferPlanifieJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transactionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    $transaction = Transactions::find($this->transactionId);

    if (!$transaction || $transaction->status !== 'planifie') {
        return;
    }

    // Vérifiez que la date actuelle correspond à la date prévue
    if (Carbon::now()->lessThan($transaction->scheduled_date)) {
        return;
    }

    $sender = $transaction->sender;
    $receiver = User::find($transaction->receiver_id);
    $montant = $transaction->montant;
    $frais = $montant * 0.01;
    $totalWithFrais = $montant + $frais;

    if ($sender->solde >= $totalWithFrais) {
        $sender->solde -= $totalWithFrais;
        $sender->save();

        $receiver->solde += $montant;
        $receiver->save();

        $transaction->status = 'completed';
        $transaction->date = Carbon::now();
        $transaction->frais = $frais;
        $transaction->save();
    } else {
        $transaction->status = 'failed';
        $transaction->save();
    }
}

    
}
