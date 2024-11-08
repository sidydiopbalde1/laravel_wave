<?php

namespace App\Repository;

interface TransactionRepositoryInterface {

    public function create(array $data);
    public function updateSenderBalance($transaction, $sender);
}