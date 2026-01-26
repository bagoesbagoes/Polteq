<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard admin
     */
    public function dashboard()
    {
        return view('admin.dashboard', [
            'title' => 'Admin Dashboard'
        ]);
    }

    /**
     * Simpan reviewer baru
     */
    public function storeReviewer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|regex:/^[\pL\s]+$/u',
            'email' => 'required|email:rfc|unique:users',
            'nidn_nuptk' => 'required|numeric|digits:17|unique:users',
            'jabatan_fungsional' => 'required|string|max:255',
            'password' => 'required|min:5|max:255',
        ], [
            // Custom error messages
            'name.required' => 'Nama wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
            'nidn_nuptk.numeric' => 'NIDN/NUPTK harus berupa angka.',
            'nidn_nuptk.digits' => 'NIDN/NUPTK harus 17 digit.',
            'nidn_nuptk.unique' => 'NIDN/NUPTK sudah terdaftar.',
            'jabatan_fungsional.required' => 'Jabatan Fungsional wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',
        ]);

        // Buat user reviewer baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nidn_nuptk' => $validated['nidn_nuptk'],
            'jabatan_fungsional' => $validated['jabatan_fungsional'],
            'password' => Hash::make($validated['password']),
            'role' => 'reviewer', // Auto-set role sebagai reviewer
        ]);

        // Log activity (optional - untuk audit)
        \Log::info('New reviewer created by admin', [
            'admin_id' => auth()->id(),
            'reviewer_id' => $user->id,
            'reviewer_email' => $user->email,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun reviewer "' . $user->name . '" berhasil dibuat!');
    }
}