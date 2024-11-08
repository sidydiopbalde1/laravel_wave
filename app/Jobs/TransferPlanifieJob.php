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
        // Trouver la transaction planifiée par son ID
        $transaction = Transactions::find($this->transactionId);

        if (!$transaction || $transaction->status !== 'planifie') {
            return;
        }

        $sender = $transaction->sender;
        $receiver = User::find($transaction->receiver_id);
        $montant = $transaction->montant;
        $frais = $montant * 0.01;
        $totalWithFrais = $montant + $frais;

        // Vérifier si l'expéditeur a un solde suffisant
        if ($sender->solde >= $totalWithFrais) {
            // Débiter le montant du solde de l'expéditeur et créditer le récepteur
            $sender->solde -= $totalWithFrais;
            $sender->save();

            $receiver->solde += $montant;
            $receiver->save();

            // Marquer la transaction comme terminée
            $transaction->status = 'completed';
            $transaction->date = Carbon::now();
            $transaction->frais = $frais;
            $transaction->save();
        } else {
            // Si le solde est insuffisant, marquer la transaction comme échouée
            $transaction->status = 'failed';
            $transaction->save();
        }
    }
}
