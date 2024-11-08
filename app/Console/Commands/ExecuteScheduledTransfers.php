<?php

namespace App\Console\Commands;

use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExecuteScheduledTransfers extends Command
{
    protected $signature = 'transfers:execute-scheduled';
    protected $description = 'Exécute les transferts planifiés dont la date est atteinte.';

    public function handle()
    {
        // Récupère les transactions planifiées à exécuter
        $transactions = Transactions::where('status', 'scheduled')
            ->where('scheduled_at', '<=', Carbon::now())
            ->get();

        foreach ($transactions as $transaction) {
            $sender = User::find($transaction->sender_id);
            $receiver = User::find($transaction->receiver_id);

            $totalWithFrais = $transaction->montant + $transaction->frais;

            if ($sender->solde >= $totalWithFrais) {
                // Exécuter la transaction
                $transaction->status = 'completed';
                $transaction->save();

                $sender->decrement('solde', $totalWithFrais);
                $receiver->increment('solde', $transaction->montant);

                $this->info("Transaction ID {$transaction->id} exécutée avec succès.");
            } else {
                // Marquer comme échouée
                $transaction->status = 'failed';
                $transaction->save();
                
                $this->warn("Échec de la transaction ID {$transaction->id} : solde insuffisant.");
            }
        }

        return 0;
    }
}
