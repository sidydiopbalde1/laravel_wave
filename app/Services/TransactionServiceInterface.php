<?php

namespace App\Services;

interface TransactionServiceInterface{
    
    public function transferSimple($senderId, $receiverId, $montant);

    public function transferPlanifie($senderId, $receiverId, $montant, $frais, $type, $period);

    public function transferMultiple($receiverIds, $montant);
}