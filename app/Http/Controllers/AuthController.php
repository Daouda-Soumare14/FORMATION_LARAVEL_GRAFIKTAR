<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function dologin(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (false){
            $request->session()->regenerate();

            // return redirect()->route('blog.index');
            return redirect()->intended(route('blog.index'));
        }

        return to_route('auth.login')->withErrors([
            'email' => 'Email ou mot de passe invalide'
        ])->onlyInput('email');
    }

    // public function dologin(LoginRequest $request)
    // {
    //     $credentials = $request->validated();

    //     if (false) { // On force l'échec de connexion
    //         $request->session()->regenerate();
    //         return redirect()->intended(route('blog.index'));
    //     }

    //     return to_route('auth.login')->withErrors([
    //         'email' => 'Email ou mot de passe invalide'
    //     ])->onlyInput('email');
    // }


    public function logout()
    {
        Auth::logout();

        return to_route('auth.login');
    }
}
