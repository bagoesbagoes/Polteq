<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form forgot password
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password'
        ]);
    }

    /**
     * Verifikasi email + NIDN/NUPTK
     */
    public function verifyIdentity(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'nidn_nuptk' => 'required|string|min:10|max:16',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
            'nidn_nuptk.numeric' => 'NIDN/NUPTK harus berupa angka.',
            'nidn_nuptk.digits' => 'NIDN/NUPTK harus 10 / 16 digit.',
        ]);

        // Rate Limiting: Cek apakah user sudah coba lebih dari 3x dalam 10 menit
        $attempts = session('reset_attempts', 0);
        $lastAttempt = session('last_reset_attempt');

        if ($attempts >= 3 && $lastAttempt) {
            // 1. Pastikan format tanggal aman & Timezone konsisten
            $lastAttemptTime = Carbon::parse($lastAttempt);
            
            // 2. Hitung waktu kapan user boleh mencoba lagi (Waktu blokir habis)
            $unlockTime = $lastAttemptTime->copy()->addMinutes(10);

            // 3. Cek apakah SEKARANG masih sebelum waktu unlock?
            if (now()->lessThan($unlockTime)) {
                // Hitung sisa waktu dalam menit (float)
                $minutesLeft = now()->floatDiffInMinutes($unlockTime);
                
                // 4. Bulatkan ke atas (ceil) agar jadi bilangan bulat (Integer)
                // Contoh: 0.1 menit jadi 1 menit, 9.5 menit jadi 10 menit
                $minutesText = ceil($minutesLeft);

                return back()->withErrors([
                    'rate_limit' => 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $minutesText . ' menit.'
                ])->withInput();
            } else {
                // Jika sudah lewat 10 menit, reset session agar user bisa coba lagi
                session()->forget(['reset_attempts', 'last_reset_attempt']);
                // Reset variabel local juga untuk logika di bawahnya
                $attempts = 0; 
            }
        }

        // Cari user berdasarkan email DAN NIDN/NUPTK
        $user = User::where('email', $validated['email'])
                    ->where('nidn_nuptk', $validated['nidn_nuptk'])
                    ->first();

        // Update attempts
        session(['reset_attempts' => $attempts + 1]);
        session(['last_reset_attempt' => now()]);

        if (!$user) {
            return back()->withErrors([
                'identity' => 'Email dan NIDN/NUPTK tidak cocok dengan data kami.'
            ])->withInput($request->only('email'));
        }

        // Generate token untuk keamanan
        $token = Str::random(64);

        // Simpan token di tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'email' => $validated['email'],
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Reset attempts counter karena berhasil
        session()->forget(['reset_attempts', 'last_reset_attempt']);

        // Redirect ke halaman reset password dengan token
        return redirect()
            ->route('password.reset', ['token' => $token, 'email' => $validated['email']])
            ->with('success', 'Identitas terverifikasi! Silakan masukkan password baru Anda.');
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5|confirmed',
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek apakah token valid (belum expired)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->first();

        if (!$resetRecord) {
            return back()->withErrors([
                'token' => 'Token reset password tidak valid.'
            ]);
        }

        // Cek apakah token sudah expired (lebih dari 1 jam)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            // Hapus token yang expired
            DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->delete();

            return back()->withErrors([
                'token' => 'Token reset password sudah kadaluarsa. Silakan ulangi proses dari awal.'
            ]);
        }

        // Cari user
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User tidak ditemukan.'
            ]);
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Hapus token setelah berhasil reset
        DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->delete();

        // Log activity (opsional - untuk security audit)
        \Log::info('Password reset successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        // Redirect ke login dengan pesan sukses
        return redirect()
            ->route('signin')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}