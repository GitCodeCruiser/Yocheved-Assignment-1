<?php
namespace App\Http\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthService{
    public function login($request){
        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $token = $user->createToken('YourAppName')->plainTextToken;
            $user['token'] = $token;
            return $user;
        }

        throw new Exception('Please provide valid credentials', Response::HTTP_UNAUTHORIZED);
    }
}