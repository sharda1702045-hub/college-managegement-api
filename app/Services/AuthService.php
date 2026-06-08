<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Dynamically ensure the default 'staff' role exists
        if (!Role::where('name', 'staff')->where('guard_name', 'web')->exists()) {
            Role::create(['name' => 'staff', 'guard_name' => 'web']);
        }

        $user->assignRole('staff');

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }

        // Only users with Admin or Super Admin role are allowed to log in to the API
        if (!$user->hasAnyRole(['Super Admin', 'Admin', 'admin'])) {
            throw ValidationException::withMessages([
                'email' => ['Access denied: only administrators are allowed to access the API.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
