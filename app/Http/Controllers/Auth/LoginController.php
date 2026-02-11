<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Cek user exists
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email not found.',
            ])->onlyInput('email');
        }
        
        // Cek user active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact administrator.',
            ])->onlyInput('email');
        }
        
        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->onlyInput('email');
        }
        
        // Login
        Auth::login($user, $request->boolean('remember'));
        
        // Regenerate session
        $request->session()->regenerate();
        
        // Redirect based on role
        if ($user->role && $user->role->is_superadmin) {
            return redirect()->intended('/dashboard');
        }
        
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}