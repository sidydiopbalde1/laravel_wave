<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserServiceImpl;
use Illuminate\Support\Facades\Log;
use App\Services\QrCodeService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceImpl $userService)
    {
        $this->userService = $userService;
    }


    public function createUser(StoreUserRequest $request)
    {
       
        try {
            $userData = $request->validated(); 
            $userData['code_secret'] = str_pad(rand(0, 999999), 4, '0', STR_PAD_LEFT);
            $userData['password'] = bcrypt($userData['password']); // Hachage du mot de passe
            // dd($userData);
            Log::info($userData);
            $user = $this->userService->createUser($userData);
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur.'], 500);
        }
    }

    //get users
    public function getUsers(){
        $users = $this->userService->getUsers();
        return response()->json([
            'success'=>true,
            'data' => $users,
            'message'=>'List users successfully',
         'statusCode'=>200]);
    }

    //get connected users
    public function getConnectedUsers(){
        $connectedUsers = $this->userService->getConnectedUsers();
        return response()->json([
            'success'=>true,
            'data' => $connectedUsers,
            'message'=>'info connected user successfully',
         'statusCode'=>200]);
    }

//     public function testQrCode()
// {
//     $qrCodeService = new QrCodeService();
//     $data = "Test QR Code"; // Données d'exemple
//     $qrCodeImage = $qrCodeService->generateQrCode($data);

//     return response()->json(['qrCode' => $qrCodeImage]);
// }
}
