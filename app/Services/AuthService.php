<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registerUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function loginUser(array $data)
    {
        if (!auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return false; 
        }

        $user = auth()->user();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logoutUser($user)
    {
        $user->currentAccessToken()->delete();
        return true;
    } 
}