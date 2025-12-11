<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index',[
            'title' => 'Register',
            'active' => 'register',
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|min:2|max:255|regex:/^[\pL\s]+$/u',
        'username' => 'required|min:3|max:255|unique:users',
        'email' => 'required|email:dns|unique:users',
        'password' => 'required|min:5|max:255'
    ]);

    // Laravel otomatis hash karena cast => 'password' => 'hashed'
    User::create($validated);

    return redirect('/signin')->with('success', 'Registration successful');
}

}
