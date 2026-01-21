<?php
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublisherController;

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
    Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotForm'])
        ->name('password.request');
    
    Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'verifyIdentity'])
        ->name('password.verify');
    
    // Reset Password Routes
    Route::get('/reset-password/{token}', [App\Http\Controllers\PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    
    Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'reset'])
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

    // ==========================
    // DASHBOARD
    // ==========================
    Route::get('/ManajemenProposalPenelitian', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ==========================
    // PROFILE
    // ==========================
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
            // 'username' => 'required|min:3|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:5|max:255',
            'nidn_nuptk' => 'required|string|min:10|max:16|unique:users,nidn_nuptk,' . $user->id,
            'jabatan_fungsional' => 'required|string|max:255',
        ]);

        $user->name = $validated['name'];
        // $user->username = $validated['username'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    })->name('profile.update');

    // ==========================
    // OTHER PAGES
    // ==========================
    Route::get('/ManajemenLaporanPKM', function () {
        return view('ManajemenLaporanPKM', ['title' => 'Manajemen Laporan PKM']);
    });

    Route::get('/ManajemenProposalPKM', function () {
        return view('ManajemenProposalPKM', ['title' => 'Manajemen Proposal PKM']);
    });

    Route::get('/LaporanPenelitian', function () {
        return view('LaporanPenelitian', ['title' => 'Manajemen Laporan Penelitian']);
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
    // ADMIN ROUTES
    // ==========================
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/reviewer/create', [AdminController::class, 'createReviewer']);
        Route::post('/admin/reviewer/create', [AdminController::class, 'storeReviewer']);
    });

    // ==========================
    // PROPOSAL RESOURCE ROUTES
    // HARUS DI PALING BAWAH!
    // ==========================
    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/submit', [ProposalController::class, 'submit'])
        ->name('proposals.submit');
});