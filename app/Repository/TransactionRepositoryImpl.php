<?php

namespace App\Repository;

use App\Models\Transactions;
use Carbon\Carbon;

class TransactionRepositoryImpl implements TransactionRepositoryInterface
{
    /**
     * Créer une nouvelle transaction.
     */
    public function create(array $data)
    {
        return Transactions::create($data);
    }

    /**
     * Récupérer une transaction par son ID.
     */
    public function findById($id)
    {
        return Transactions::findOrFail($id);
    }

    // /**
    //  * Mettre à jour le solde d'un utilisateur pour une transaction.
    //  */
    public function updateSenderBalance($transaction, $sender)
    {
        $sender->solde -= $transaction->montant + $transaction->frais;
        $sender->save();
    }

    // /**
    //  * Mettre à jour le solde d'un récepteur pour une transaction.
    //  */
    public function updateReceiverBalance($transaction, $receiver)
    {
        $receiver->solde += $transaction->montant;
        $receiver->save();
    }

    // /**
    //  * Créer une transaction planifiée (pour une exécution future).
    //  */
    // public function createScheduledTransaction($senderId, $receiverId, $montant, $frais, $type, $date)
    // {
    //     return Transactions::create([
    //         'montant' => $montant,
    //         'status' => 'pending',
    //         'date' => $date,
    //         'solde_sender' => null,
    //         'solde_receiver' => null,
    //         'frais' => $frais,
    //         'type' => $type,
    //         'sender_id' => $senderId,
    //         'receiver_id' => $receiverId,
    //         'receiver_string' => null,
    //     ]);
    // }
}
