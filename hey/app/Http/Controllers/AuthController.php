<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }

            return redirect()->intended($this->redirectByRole($user->role));
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
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

        Auth::login($user);
        return redirect()->route('seller.dashboard')->with('success', 'Welcome to LDMS!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(string $role): string
    {
        return match($role) {
            'admin'  => route('admin.dashboard'),
            'seller' => route('seller.dashboard'),
            'driver' => route('driver.dashboard'),
            default  => route('login'),
        };
    }
}
