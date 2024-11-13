<?php
namespace App\Http\Controllers;

use App\Services\AuthentificationPassport;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthentificationPassport $authpassport)
    {
        $this->authService = $authpassport;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('telephone', 'password');
        // Log::info($credentials);

        $result = $this->authService->authenticate($credentials);
        return response()->json($result);
    }

    public function logout()
    {
        // Appeler la méthode logout() du service sélectionné
        $this->authService->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

