<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('Signin',[
            'title' => 'Sign In'
        ]);
    }

    public function authenticate (Request $request)
    {
        $credentials = $request->validate([
            // 'email' => 'required|email:dns',
            'email' => 'required|email:rfc',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()
                ->intended('/UsulanPenelitian')
                ->with('success', 'Login berhasil!');
        }

        return back()->with('loginError', 'Login failed! Please check your email and password.');
    }

    public function logout(Request $request)
    {
        // Akhiri sesi otentikasi
        Auth::logout(); 

        // Invalidate session saat ini
        $request->session()->invalidate(); 

        // Regenerate token CSRF
        $request->session()->regenerateToken(); 

        // Redirect kembali ke halaman utama atau signin
        return redirect('/'); 
        // Atau: return redirect('/signin')->with('success', 'Anda telah berhasil keluar.');
    }

    public function showLogin()
    {
        return view('Signin', [
            'title' => 'Sign In'
        ]);
    }

}