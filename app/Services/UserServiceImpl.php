<?php

namespace App\Services;

use App\Repository\UserRepositoryImpl;
use App\Jobs\AuthJob;

class UserServiceImpl implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryImpl $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        // Générer un code secret aléatoire à 6 chiffres
    

        // Création de l'utilisateur
        $user = $this->userRepository->create($data);

        // Envoi des informations d'authentification par email
        AuthJob::dispatch(
            $user->email,
            $data['password'],
            $user->nom,
            $user->prenom,
            $data['code_secret']
        );

        return $user;
    }
}
