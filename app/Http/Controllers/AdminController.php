<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
            'nidn_nuptk' => [
                'required',
                'unique:users',
                'string',
                'regex:/^[0-9]+$/',
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
            'jabatan_fungsional' => 'required|string|max:255',
            'password' => 'required|min:5|max:255',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
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
            'role' => 'reviewer',
        ]);

        // Log activity
        Log::info('New reviewer created by admin', [
            'admin_id' => auth()->id(),
            'reviewer_id' => $user->id,
            'reviewer_email' => $user->email,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun reviewer "' . $user->name . '" berhasil dibuat!');
    }

    /**
     * Update reviewer
     */
    public function updateReviewer(Request $request, User $user)
    {
        // Pastikan user yang diedit adalah reviewer
        if ($user->role !== 'reviewer') {
            abort(403, 'Hanya reviewer yang dapat diedit melalui halaman ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|regex:/^[\pL\s]+$/u',
            'email' => 'required|email:rfc|unique:users,email,' . $user->id,
            'nidn_nuptk' => [
                'required',
                'string',
                'regex:/^[0-9]+$/',  // Hanya angka
                function ($attribute, $value, $fail) {
                    $length = strlen($value);
                    if ($length !== 10 && $length !== 16) {
                        $fail('NIDN/NUPTK harus tepat 10 digit (NIDN) atau 16 digit (NUPTK).');
                    }
                },
                'unique:users,nidn_nuptk,' . $user->id
            ],
            'jabatan_fungsional' => 'required|string|max:255',
            'password' => 'nullable|min:5|max:255',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nidn_nuptk.required' => 'NIDN/NUPTK wajib diisi.',
            'nidn_nuptk.unique' => 'NIDN/NUPTK sudah terdaftar.',
            'jabatan_fungsional.required' => 'Jabatan Fungsional wajib diisi.',
            'password.min' => 'Password minimal 5 karakter.',
        ]);

        // Update data reviewer
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->nidn_nuptk = $validated['nidn_nuptk'];
        $user->jabatan_fungsional = $validated['jabatan_fungsional'];
        
        // Update password hanya jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Log activity
        Log::info('Reviewer updated by admin', [
            'admin_id' => auth()->id(),
            'reviewer_id' => $user->id,
            'reviewer_email' => $user->email,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data reviewer "' . $user->name . '" berhasil diupdate!');
    }

    /**
     * Delete reviewer
     */
    public function deleteReviewer(User $user)
    {
        // Pastikan user yang dihapus adalah reviewer
        if ($user->role !== 'reviewer') {
            abort(403, 'Hanya reviewer yang dapat dihapus melalui halaman ini.');
        }

        // Cek apakah reviewer punya review aktif
        $reviewCount = $user->reviews()->count();
        
        if ($reviewCount > 0) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Reviewer "' . $user->name . '" tidak dapat dihapus karena memiliki ' . $reviewCount . ' review aktif. Hapus review terlebih dahulu.');
        }

        $reviewerName = $user->name;
        
        // Log activity sebelum delete
        Log::info('Reviewer deleted by admin', [
            'admin_id' => auth()->id(),
            'reviewer_id' => $user->id,
            'reviewer_name' => $reviewerName,
            'reviewer_email' => $user->email,
        ]);

        $user->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Akun reviewer "' . $reviewerName . '" berhasil dihapus!');
    }

    public function pkmIndex(Request $request)
    {
        $query = \App\Models\PkmProposal::with('author')
            ->whereNotIn('status', ['draft']);

        // SEARCH: Judul atau Nama Author
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('author', function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // FILTER: Status
        if ($request->filled('status')) {
            $validStatuses = ['submitted', 'accepted', 'need_revision'];
            if (in_array($request->status, $validStatuses)) {
                $query->where('status', $request->status);
            }
        }

        // FILTER: Author/Publisher
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        // FILTER: Tahun
        if ($request->filled('tahun')) {
            $query->where('tahun_pelaksanaan', $request->tahun);
        }

        // FILTER: Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_pkm', $request->kategori);
        }

        // SORT
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest('created_at');
        } else {
            $query->latest('created_at');
        }

        $pkms = $query->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => \App\Models\PkmProposal::whereNotIn('status', ['draft'])->count(),
            'submitted' => \App\Models\PkmProposal::where('status', 'submitted')->count(),
            'accepted' => \App\Models\PkmProposal::where('status', 'accepted')->count(),
            'revisi' => \App\Models\PkmProposal::where('status', 'need_revision')->count(),
        ];

        // Get publishers for filter
        $publishers = \App\Models\User::where('role', 'publisher')
            ->orderBy('name')
            ->get();

        // Get unique years
        $years = \App\Models\PkmProposal::select('tahun_pelaksanaan')
            ->distinct()
            ->orderBy('tahun_pelaksanaan', 'desc')
            ->pluck('tahun_pelaksanaan');

        // Get unique categories
        $categories = \App\Models\PkmProposal::select('kategori_pkm')
            ->distinct()
            ->orderBy('kategori_pkm')
            ->pluck('kategori_pkm');

        return view('admin.pkm.index', [
            'title' => 'Kelola Usulan PKM',
            'pkms' => $pkms,
            'stats' => $stats,
            'publishers' => $publishers,
            'years' => $years,
            'categories' => $categories,
        ]);
    }       
}