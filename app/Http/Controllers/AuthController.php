<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request){
        try 
        {
            $user = $this->authService->login($request);
            return $this->sendResponse("Logged successfully", Response::HTTP_OK, $user);
        } 
        catch (Exception $exception) 
        {
            return $this->sendResponse($exception->getMessage(), $exception->getCode(), null, false);
        }
    }
}
