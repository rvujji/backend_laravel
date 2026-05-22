<?php

namespace App\Services;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(array $data): array
    {
        $user = User::create([

            'name' => $data['name'],

            'email' => $data['email'],

            'phone' => $data['phone'] ?? null,

            'password' => Hash::make($data['password']),

            'status' => 'active',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ROLE
        |--------------------------------------------------------------------------
        */

        $user->assignRole('student');

        /*
        |--------------------------------------------------------------------------
        | CREATE TOKEN
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | INVALID USER
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | INVALID PASSWORD
        |--------------------------------------------------------------------------
        */

        if (!Hash::check($data['password'], $user->password)) {

            throw ValidationException::withMessages([
                'password' => ['Invalid credentials']
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE TOKEN
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | UPDATE LAST LOGIN
        |--------------------------------------------------------------------------
        */

        $user->update([
            'last_login_at' => now()
        ]);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
