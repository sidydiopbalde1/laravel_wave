<?php

namespace App\Http\Controllers;

use App\Services\TransactionServiceImpl;
use Illuminate\Http\Request;
use App\Http\Requests\TransferRequest;
use App\Jobs\TransferPlanifieJob;
use App\Models\Transactions;
class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionServiceImpl $transactionService)
    {
        $this->transactionService = $transactionService;
    }

        /**
     * Effectuer un transfert multiple.
     */
    public function transferMultiple(Request $request)
    {
        $data = $request->validate(
            [
               'telephones' => 'required|array',
               'montant' => 'required|numeric'
            ]
        );
        $result = $this->transactionService->transferMultiple(
            $data['telephones'],
            $data['montant']
        );
        
        // dd($data);
        return response()->json(
            ["success" =>true,
            "message" => "Transfert multiple effectué avec succès",
            "data" => $result
            ]
        );
    }

    /**
     * Annulation d'une transaction
     */
    public function cancelTransaction()
    {
        $id = request('id');
       
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID de transaction manquant.',
            ], 400);
        }
    
        // Appel au service pour annuler la transaction
        $response = $this->transactionService->cancelTransaction($id);
    
        // Retourner la réponse JSON
        return response()->json($response, $response['success'] ? 200 : 500);
    }
    
   
    /**
     * Effectuer un transfert simple.
     */
    public function transferSimple(TransferRequest $request)
    {
        try {
        $data = $request->validate(
            [
                'sender_id' => 'required|exists:users,id',
                'receiver_id' => 'required|exists:users,id',
                'montant' => 'required|numeric'
            ]
        );
        
        // return response()->json('sidy');
        $result = $this->transactionService->transferSimple(
            $data['sender_id'],
            $data['receiver_id'],
            $data['montant']
        );

        return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => $e->getMessage()], 500);

        }
    }

    public function getTransferHistory()
    {
        // Récupérer l'ID de l'utilisateur connecté avec Passport
        $userId = auth()->id();
        // dd($userId);
        // Vérifier si l'utilisateur est connecté
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non connecté',
            ], 401);
        }
    
        $transactions = $this->transactionService->getTransferHistory($userId);
    
        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }
    

    /**
     * Effectuer un transfert planifié.
     */
    public function transferPlanifie(Request $request)
    {
        $data = $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'montant' => 'required|numeric',
            'frais' => 'required|numeric',
            'type' => 'required|string',
            'period' => 'required|string', // 'day', 'week', 'month'
        ]);

        $result = $this->transactionService->transferPlanifie(
            $data['sender_id'],
            $data['receiver_id'],
            $data['montant'],
            $data['frais'],
            $data['type'],
            $data['period']
        );

        return response()->json($result);
    }
    public function createScheduledTransfer(Request $request)
    {
        $frequency = $request->input('frequency');
        
        // Créer la transaction initiale
        $transaction = Transactions::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'montant' => $request->montant,
            'status' => 'planifie',
        ]);
    
        // Planifier le job récurrent en fonction de la fréquence
        switch ($frequency) {
            case 'daily':
                TransferPlanifieJob::dispatch($transaction->id)->delay(now()->addDay());
                break;
    
            case 'weekly':
                TransferPlanifieJob::dispatch($transaction->id)->delay(now()->addWeek());
                break;
    
            case 'monthly':
                TransferPlanifieJob::dispatch($transaction->id)->delay(now()->addMonth());
                break;
    
            default:
                return response()->json(['error' => 'Fréquence non valide.'], 400);
        }
    
        return response()->json(['message' => 'Transfert planifié avec succès pour une fréquence ' . $frequency]);
    }

}

