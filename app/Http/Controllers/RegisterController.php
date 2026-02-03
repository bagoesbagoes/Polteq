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
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:5|max:255',
            'nidn_nuptk' => [
                'required',
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Cek hanya angka
                    if (!ctype_digit($value)) {
                        $fail('NIDN/NUPTK harus berupa angka.');
                        return;
                    }
                    
                    // Cek panjang 10 atau 16
                    $length = strlen($value);
                    if ($length !== 10 && $length !== 16) {
                        $fail('NIDN/NUPTK harus tepat 10 digit (NIDN) atau 16 digit (NUPTK).');
                    }
                },
            ],
            'jabatan_fungsional' => 'required|in:Asisten Ahli,Lektor,Lektor Kepala,Guru Besar,Tenaga Pengajar,Instruktur',
            'prodi' => 'required|in:English for Business & Professional Communication,Bisnis Kreatif,Teknologi Produksi Tanaman Perkebunan,Teknologi Pangan',
        ],
        [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',
            'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
            'nidn_nuptk.unique' => 'NIDN/NUPTK sudah terdaftar.',
            'jabatan_fungsional.required' => 'Jabatan Fungsional wajib diisi.',
            'jabatan_fungsional.in' => 'Jabatan Fungsional yang dipilih tidak valid.',
            'prodi.required' => 'Program Studi wajib diisi.',
            'prodi.in' => 'Program Studi yang dipilih tidak valid.',
        ]);

        User::create($validated);

        return redirect('/signin')->with('success', 'Registration successful');
    }
}