<?php
namespace App\Services;

use App\Repository\TransferRepositoryImpl;

class TransferService
{
    protected $transferRepository;

    public function __construct(TransferRepositoryImpl $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    public function simpleTransfer($amount, $to)
    {
        // Implémenter la logique de transfert simple
        return $this->transferRepository->createTransfer($amount, $to);
    }

    public function addToFavorites($number)
    {
        // Implémenter la logique pour mettre un numéro en favori
        return $this->transferRepository->addToFavorites($number);
    }

    public function cancelTransfer($transferId)
    {
        // Implémenter la logique d'annulation de transfert
        return $this->transferRepository->cancelTransfer($transferId);
    }

    public function scheduleTransfer($amount, $to, $scheduleTime)
    {
        // Implémenter la logique pour planifier un transfert
        return $this->transferRepository->scheduleTransfer($amount, $to, $scheduleTime);
    }

    public function cancelScheduledTransfer($scheduledTransferId)
    {
        // Implémenter la logique pour annuler un transfert programmé
        return $this->transferRepository->cancelScheduledTransfer($scheduledTransferId);
    }

    public function multipleTransfer($amount, $numbers)
    {
        // Implémenter la logique pour un transfert multiple
        return $this->transferRepository->multipleTransfer($amount, $numbers);
    }
}
