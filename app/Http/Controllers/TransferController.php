<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\TransferPlanifieJob;

class TransferController extends Controller
{
    /**
     * Crée un transfert planifié via l'API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function PlanifierTransfer(Request $request)
    {
        // Validation de la requête
        $request->validate([
            'receiver_phones' => 'required|array', 
            'receiver_phones.*' => 'required|distinct|exists:users,telephone', 
            'montant' => 'required|numeric|min:0.01',
            'planified_date' => 'required|date|after_or_equal:today', // La date doit être aujourd'hui ou dans le futur
        ]);

        // Récupérer l'utilisateur connecté (expéditeur)
        $sender_id = auth()->id();

        // Récupérer les utilisateurs correspondant aux numéros de téléphone
        $receivers = User::whereIn('telephone', $request->receiver_phones)->get();

        // Si aucun utilisateur n'est trouvé avec ces numéros, retournez une erreur
        if ($receivers->isEmpty()) {
            return response()->json([
                'error' => 'Aucun destinataire trouvé pour les numéros fournis.',
            ], 404);
        }
        $planifiedDate = $request->planified_date . 'T00:00:00'; 
        // Créer une nouvelle transaction pour chaque destinataire
        $transactions = [];
        foreach ($receivers as $receiver) {
            $transaction = new Transactions();
            $transaction->sender_id = $sender_id;
            $transaction->receiver_id = $receiver->id;
            $transaction->montant = $request->montant;
            $transaction->date = Carbon::now();  
            $transaction->scheduled_date = Carbon::parse($planifiedDate);
            $transaction->frais = $request->montant * 0.01;
            $transaction->type = 'transfert';
            $transaction->status = 'planifie'; // Marquer comme planifiée
            $transaction->save();


              // Planifier l'exécution du job à la date demandée
              TransferPlanifieJob::dispatch($transaction->id)
              ->delay($transaction->scheduled_date->diffInSeconds(Carbon::now(), false));


            $transactions[] = $transaction;
        }

        // Retourner une réponse JSON avec succès et les transactions créées
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Votre transfert a été planifié avec succès.',
            'data' => $transactions,
        ], 200);
    }

    /**
     * Affiche les transferts planifiés de l'utilisateur via l'API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $transfers = Transactions::where('sender_id', auth()->id())
            ->where('status', 'planifie')
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Retourner les transferts planifiés sous forme de réponse JSON
        return response()->json([
            'transfers' => $transfers,
        ]);
    }

    /**
     * Annuler un transfert planifié via l'API.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel($id)
    {
        $transaction = Transactions::findOrFail($id);

        // Vérifier que l'utilisateur est bien l'expéditeur de la transaction
        if ($transaction->sender_id !== auth()->id()) {
            return response()->json([
                'error' => 'Vous ne pouvez pas annuler ce transfert.',
            ], 403);
        }

        // Annuler la transaction
        $transaction->status = 'annule';
        $transaction->save();

        return response()->json([
            'message' => 'Votre transfert a été annulé avec succès.',
            'transaction' => $transaction,
        ]);
    }
}
