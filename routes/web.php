<?php
use App\Models\User;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\PasswordResetController;

// ==========================
// PUBLIC ROUTES (TANPA LOGIN)
// ==========================

// Halaman Landing -> redirect ke signin
Route::get('/', function () {
    return redirect()->route('signin');
})->name('home');

// Login & Signup hanya bisa diakses oleh pengguna yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/signin', [LoginController::class, 'index'])->name('signin');
    Route::post('/signin', [LoginController::class, 'authenticate'])->name('login');

    Route::get('/signup', [RegisterController::class, 'index'])->name('signup');
    Route::post('/signup', [RegisterController::class, 'store'])->name('signup.process');

    // Forgot Password Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->name('password.request');
    
    Route::post('/forgot-password', [PasswordResetController::class, 'verifyIdentity'])
        ->name('password.verify');
    
    // Reset Password Routes
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])
        ->name('password.update');

});

// ==========================
// LOGOUT
// ==========================

Route::post('/signout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/signin');
})->name('signout');

// ==========================
// ROUTES YANG WAJIB LOGIN
// ==========================
Route::middleware('auth')->group(function () {

    Route::get('/proposals/{proposal}/download-surat-kerja', [ProposalController::class, 'downloadSuratKerja'])
    ->middleware('auth')
    ->name('proposals.download-surat-kerja');

    Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    // Create Reviewer (POST)
    Route::post('/admin/reviewer/create', [AdminController::class, 'storeReviewer'])
        ->name('admin.store-reviewer');
    });

    // DASHBOARD
    Route::get('/UsulanPenelitian', [DashboardController::class, 'index'])
        ->name('dashboard');

    // PROFILE
    Route::get('/profile', function () {
        return view('profile', [
            'title' => 'Profile',
            'user' => Auth::user()
        ]);

    });

    Route::get('/upload', function () {
        return view('upload', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    })->name('profile.edit');

    Route::post('/profile/update', function (Request $request) {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:5|max:255',
            'nidn_nuptk' => 'required|string|min:10|max:16|unique:users,nidn_nuptk,' . $user->id,
            'jabatan_fungsional' => 'required|string|max:255',
            'prodi' => 'required|in:English for Business & Professional Communication,Bisnis Kreatif,Teknologi Produksi Tanaman Perkebunan,Teknologi Pangan',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->nidn_nuptk = $validated['nidn_nuptk'];
        $user->jabatan_fungsional = $validated['jabatan_fungsional'];
        $user->prodi = $validated['prodi'];
    
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    // ==============================
    // ==Halaman Laporan Penelitian==
    // ==============================

    Route::get('/LaporanPenelitian', [DashboardController::class, 'IndexLaporanPenelitian'])
        ->name('laporan_penelitian');

    // ==============================
    // == PUBLISHER: Reports Routes ==
    // ==============================
    Route::middleware(['auth', 'role:publisher'])->group(function () {
        
        // Laporan Akhir
        Route::get('/reports/laporan-akhir', [ReportController::class, 'index'])
            ->defaults('type', 'laporan_akhir')
            ->name('reports.laporan-akhir');
        
        Route::get('/reports/laporan-akhir/create', [ReportController::class, 'create'])
            ->defaults('type', 'laporan_akhir')
            ->name('reports.create-laporan-akhir');
        
        Route::post('/reports/laporan-akhir', [ReportController::class, 'store'])
            ->defaults('type', 'laporan_akhir')
            ->name('reports.store-laporan-akhir');
        
        // Luaran
        Route::get('/reports/luaran', [ReportController::class, 'index'])
            ->defaults('type', 'luaran')
            ->name('reports.luaran');
        
        Route::get('/reports/luaran/create', [ReportController::class, 'create'])
            ->defaults('type', 'luaran')
            ->name('reports.create-luaran');
        
        Route::post('/reports/luaran', [ReportController::class, 'store'])
            ->defaults('type', 'luaran')
            ->name('reports.store-luaran');
    });

    // ==============================
    // ADMIN: Manage All Reports 
    // ==============================
    Route::middleware(['auth', 'role:admin'])->group(function () {
        
        Route::get('/admin/reports/laporan-akhir', [ReportController::class, 'adminIndex'])
            ->defaults('type', 'laporan_akhir')
            ->name('admin.reports.laporan-akhir');
        
        Route::get('/admin/reports/luaran', [ReportController::class, 'adminIndex'])
            ->defaults('type', 'luaran')
            ->name('admin.reports.luaran');
    });

    // ==============================
    // (Publisher & Admin) untuk show, delete dan download (khusus admin)
    // ==============================
    Route::middleware('auth')->group(function () {
        
        Route::get('/reports/{type}/{report}', [ReportController::class, 'show'])
            ->whereIn('type', ['laporan_akhir', 'luaran'])
            ->name('reports.show');
        
        Route::delete('/reports/{type}/{report}', [ReportController::class, 'destroy'])
            ->whereIn('type', ['laporan_akhir', 'luaran'])
            ->name('reports.destroy');
        
        Route::get('/reports/{type}/{report}/download', [ReportController::class, 'download'])
            ->whereIn('type', ['laporan_akhir', 'luaran'])
            ->name('reports.download');
    });
    
    
    // ==========================
    // OTHER PAGES
    // ==========================
    Route::get('/LaporanPKM', function () {
        return view('LaporanPKM', ['title' => 'Manajemen Laporan PKM']);
    });

    Route::get('/UsulanPKM', function () {
        return view('UsulanPKM', ['title' => 'Manajemen Proposal PKM']);
    });

    // ==========================
    // PROPOSALS ROUTES
    // PENTING: Route khusus HARUS di atas Route::resource!
    // ==========================
    
    Route::middleware(['auth', 'role:reviewer'])->group(function () {
    Route::get('/reviewer/proposals', [ProposalController::class, 'browseForReviewer'])
        ->name('reviewer.proposals');
    });

    // Browse proposals (untuk reviewer & admin)
    Route::get('/proposals/browse', function (Request $request) {
    $user = Auth::user();
    
    if (in_array($user->role, ['admin', 'reviewer'])) {
        // Query Builder untuk Search & Filter
        $query = Proposal::with('author')
            ->whereNotIn('status', ['draft']); // Tampilkan semua kecuali draft
        
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
        
        // FILTER: Status (hanya 4 status yang valid)
        if ($request->filled('status')) {
            $validStatuses = ['submitted', 'accepted', 'need_revision'];
            if (in_array($request->status, $validStatuses)) {
                $query->where('status', $request->status);
            }
        }
        
        // SORT: Terbaru/Terlama
        $sortBy = $request->get('sort', 'latest');
        if ($sortBy === 'oldest') {
            $query->oldest('created_at');
        } else {
            $query->latest('created_at');
        }
        
        $proposals = $query->paginate(12)->withQueryString();
        
    } else {
        // Publisher hanya lihat proposal miliknya sendiri
        $proposals = Proposal::with('author')
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate(12);
    }
    
    return view('posts', [
        'title' => 'Daftar pengajuan usulan',
        'posts' => $proposals
    ]);
    })->name('proposals.browse');

    //Usulan Disetujui - HANYA PUBLISHER
    Route::get('/proposals/accepted', [ProposalController::class, 'accepted'])
        ->middleware('role:publisher')
        ->name('proposals.accepted');

    //Revisi Usulan - HANYA PUBLISHER
    Route::get('/proposals/revisions', [ProposalController::class, 'revisions'])
    ->middleware('role:publisher')
    ->name('proposals.revisions');

    // View proposal detail
    Route::get('/proposals/view/{proposal}', [ProposalController::class, 'show'])
        ->name('proposals.view');

    // Filter by author
    Route::get('/proposals/author/{user:username}', function(User $user) {
        $currentUser = Auth::user();
        
        if (!in_array($currentUser->role, ['admin', 'reviewer'])) {
            abort(403);
        }
        
        return view('posts', [
            'title' => count($user->proposals) . ' Usulan oleh ' . $user->name,
            'posts' => $user->proposals
        ]);

    })->name('proposals.by-author');

    // ==========================
    // PUBLISHER ROUTES
    // ==========================
    Route::middleware('role:publisher')->group(function () {
        Route::get('/publisher/dashboard', [PublisherController::class, 'dashboard']);
        Route::get('/publisher/upload', [PublisherController::class, 'create']);
        Route::post('/publisher/upload', [PublisherController::class, 'store']);
        Route::get('/publisher/proposal/{proposal}', [PublisherController::class, 'show']);
        Route::post('/publisher/proposal/{proposal}/revisi', [PublisherController::class, 'submitRevision']);
    });

    // ==========================
    // REVIEWER ROUTES
    // ==========================
    Route::middleware('role:reviewer')->group(function () {
        Route::get('reviewer/dashboard', [ReviewerController::class, 'dashboard'])->name('reviewer.dashboard');
        Route::get('reviewer/my-reviews', [ReviewerController::class, 'myReviews'])->name('reviewer.my-reviews');
        Route::get('reviewer/review/{proposal}', [ReviewerController::class, 'reviewForm'])->name('reviewer.review-form');
        Route::post('reviewer/review/{proposal}', [ReviewerController::class, 'storeReview'])->name('reviewer.store-review');
        Route::get('reviewer/reviews/{review}', [ReviewerController::class, 'showReview'])->name('reviewer.show-review');
        Route::get('reviewer/reviews/{review}/edit', [ReviewerController::class, 'editReview'])->name('reviewer.edit-review');
        Route::put('reviewer/reviews/{review}', [ReviewerController::class, 'updateReview'])->name('reviewer.update-review');
        Route::delete('reviewer/reviews/{review}', [ReviewerController::class, 'deleteReview'])->name('reviewer.delete-review');
    });

    // ==========================
    // PROPOSAL RESOURCE ROUTES
    // HARUS DI PALING BAWAH!
    // ==========================
    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/submit', [ProposalController::class, 'submit'])
        ->name('proposals.submit');
});