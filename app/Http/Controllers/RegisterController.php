<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Faker\Provider\Lorem;

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
        'email' => 'required|email:dns|unique:users',
        'password' => 'required|min:5|max:255',
        'nidn_nuptk' => 'required|string|min:10|max:16|unique:users',
        'jabatan_fungsional' => 'required|string|max:255'
    ],
    [
        'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
        'nidn_nuptk.string' => 'NIDN/NUPTK harus berupa angka.',
        'nidn_nuptk.digits' => 'NIDN/NUPTK harus terdiri dari 17 digit.',
        'nidn_nuptk.unique' => 'NIDN/NUPTK sudah terdaftar.',
        'jabatan_fungsional.required' => 'Jabatan Fungsional wajib diisi.',
    ]);

    // Laravel otomatis hash karena cast => 'password' => 'hashed'
    User::create($validated);

    return redirect('/signin')->with('success', 'Registration successful');
}

}