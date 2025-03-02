<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class Authentication extends Controller
{
    public function form()
    {
        if (Auth::check()) {
            return redirect(Auth::user()->role . '/dashboard');
        }

        return view('pages.auth.login');
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(Auth::user()->role . '/dashboard');
        }
 
        return back()->withErrors([
            'email' => 'Email yang dimasukkan belum terdaftar',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
 
        $request->session()->invalidate();
 
        $request->session()->regenerateToken();
 
        return redirect('/');
    }
}
