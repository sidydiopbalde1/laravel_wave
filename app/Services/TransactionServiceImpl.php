<?php
namespace App\Services;

use App\Models\Transactions;
use App\Models\User;
use App\Repository\TransactionRepositoryInterface;
use App\Repository\TransactionRepositoryImpl;
use Carbon\Carbon;

class TransactionServiceImpl implements TransactionServiceInterface
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryImpl $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }
    /**
     * Effectuer un transfert multiple.
     */
    public function transferMultiple($telephones, $montant)
    {
        $senderId = auth()->id();
        $sender = User::find($senderId);
    
        $transactions = [
            'success' => [],
            'failed' => [],
        ];
    
        foreach ($telephones as $telephone) {
            $frais = $montant * 0.01;
            $totalWithFrais = $montant + $frais;
    
            // Vérifier si le solde est suffisant pour cette transaction
            if ($sender->solde >= $totalWithFrais) {
                $receiver = User::where('telephone', $telephone)->first();
    
                if ($receiver) {
                    // Créer la transaction
                    $transaction = $this->transactionRepository->create([
                        'montant' => $montant,
                        'status' => 'completed',
                        'date' => Carbon::now(),
                        'frais' => $frais,
                        'type' => 'transfert',
                        'sender_id' => $senderId,
                        'receiver_id' => $receiver->id,
                    ]);
    
                    // Mettre à jour le solde de l'expéditeur
                    $this->transactionRepository->updateSenderBalance($transaction, $sender);
    
                    // Mettre à jour le solde du destinataire
                    $this->transactionRepository->updateReceiverBalance($transaction, $receiver);
    
                    $transactions['success'][] = $transaction;
                } else {
                    // Si le destinataire n'existe pas
                    $transactions['failed'][] = [
                        'telephone' => $telephone,
                        'montant' => $montant,
                        'message' => 'Utilisateur avec ce téléphone non trouvé',
                    ];
                }
            } else {
                // Ajouter la transaction échouée si le solde est insuffisant
                $transactions['failed'][] = [
                    'telephone' => $telephone,
                    'montant' => $montant,
                    'message' => 'Solde insuffisant pour cette transaction',
                ];
            }
        }
    
        return $transactions;
    }

    public function cancelTransaction($transactionId)
    {
        // Trouver la transaction par son ID
        $transaction = $this->transactionRepository->findById($transactionId);
    
        // Vérifier si la transaction existe
        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Transaction introuvable.'
            ];
        }
        // verifier si la transaction a été déjà annulée
        if ($transaction->status === 'cancelled') {
            return [
                'success' => false,
                'message' => 'La transaction a déjà été annulée.'
            ];
        }
        // Vérifier si la transaction a été effectuée il y a moins de 30 minutes
        $transactionTime = Carbon::parse($transaction->date);
        if ($transactionTime->diffInMinutes(now()) > 30) {
            return [
                'success' => false,
                'message' => 'La transaction ne peut pas être annulée car elle a été effectuée il y a plus de 30 minutes.'
            ];
        }
    
        // Annuler la transaction
        $transaction->status = 'cancelled';
        $transaction->save();
    
        // Rembourser le montant au solde de l'expéditeur
        $sender = $transaction->sender;
        $sender->increment('solde', $transaction->montant + $transaction->frais);
    
        return [
            'success' => true,
            'message' => 'Transaction annulée avec succès.',
            'transaction' => $transaction
        ];
    }
    

   

       // Méthode pour obtenir l'historique des transferts
       public function getTransferHistory($userId)
       {
           return Transactions::where('sender_id', $userId)
                ->where('type', 'transfert')
               ->orderBy('date', 'desc')
               ->get();
       }
    /**
     * Effectuer un transfert simple.
     */
    public function transferSimple($senderId, $receiverId, $montant)
    {
        $sender = User::find($senderId);
        $receiver = User::find($receiverId);
        if(!$sender || !$receiver){
            return ['success' => false, 'message' => 'Utilisateurs inconnus.'];
        }
        $frais =$montant * 0.01;
        $totalAmount= $montant + $frais;
        // Vérifier si l'envoyeur a suffisamment de solde
        if ($sender->solde < $totalAmount) {
            return ['success' => false, 'message' => 'Solde insuffisant.'];
        }

        // Créer la transaction
        $transaction = $this->transactionRepository->create([
            'montant' => $montant,
            'status' => 'completed',
            'date' => Carbon::now(),
            'frais' => $frais,
            'type' => 'transfert',
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
        ]);

        // Mettre à jour les soldes
        $this->transactionRepository->updateSenderBalance($transaction, $sender);
        $this->transactionRepository->updateReceiverBalance($transaction, $receiver);

        return ['success' => true, 'transaction' => $transaction];
    }

    /**
     * Effectuer un transfert planifié.
    **/
    public function transferPlanifie($senderId, $receiverId, $montant, $frais, $type, $period)
    {
        // Calculer la date de la planification (ex: aujourd'hui + 1 jour, semaine, mois, etc.)
        $date = Carbon::now()->add($period); // Ajouter la période au moment actuel

        // Créer la transaction planifiée
        // $transaction = $this->transactionRepository->createScheduledTransaction(
        //     $senderId, $receiverId, $montant, $frais, $type, $date
        // );

        // return ['success' => true, 'transaction' => $transaction];
    }

}
