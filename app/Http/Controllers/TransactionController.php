<?php

namespace App\Http\Controllers;

use App\Services\TransactionServiceImpl;
use Illuminate\Http\Request;
use App\Http\Requests\TransferRequest;
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
               'sender_id' => 'required|exists:users,id',
               'receiver_ids' => 'required|array',
               'montant' => 'required|numeric'
            ]
        );
        $result = $this->transactionService->transferMultiple(
            $data['sender_id'],
            $data['receiver_ids'],
            $data['montant']
        );
        
        // dd($data);
        return response()->json($result);
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
        $userId = request('user_id');
        // dd($userId);
        $transactions = $this->transactionService->getTransferHistory($userId);

        return response()->json(
            [
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


}

