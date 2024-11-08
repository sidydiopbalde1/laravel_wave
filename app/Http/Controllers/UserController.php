<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserServiceImpl;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceImpl $userService)
    {
        $this->userService = $userService;
    }


    public function createUser(StoreUserRequest $request)
    {
        // Les données validées sont accessibles ici
        // try {
            $userData = $request->validated(); 
            $userData['code_secret'] = str_pad(rand(0, 999999), 4, '0', STR_PAD_LEFT);
            $userData['password'] = bcrypt($userData['password']); // Hachage du mot de passe
            // dd($userData);
            $user = $this->userService->createUser($userData);
            return response()->json([
                'message' => 'Utilisateur créé avec succès.',
                'user' => $user
            ], 201);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur.'], 500);
        // }
    }
}
