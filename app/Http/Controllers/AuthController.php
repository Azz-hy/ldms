<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Your account has been deactivated.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->userPayload($user),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'unique:users'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'password'         => ['required', 'confirmed', Password::min(8)],
            'business_name'    => ['nullable', 'string', 'max:255'],
            'business_address' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'seller',
        ]);

        Seller::create([
            'user_id'          => $user->id,
            'business_name'    => $data['business_name'] ?? null,
            'business_address' => $data['business_address'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $this->userPayload($user),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }

    private function userPayload(User $user): array
    {
        $payload = [
            'id'            => $user->id,
            'name'          => $user->name,
            'email'         => $user->email,
            'role'          => $user->role,
            'phone'         => $user->phone,
        ];

        if ($user->isSeller() && $user->seller) {
            $payload['business_name'] = $user->seller->business_name;
        }

        if ($user->isDriver() && $user->driver) {
            $payload['vehicle_type']   = $user->driver->vehicle_type;
            $payload['vehicle_number'] = $user->driver->vehicle_number;
        }

        return $payload;
    }
}
