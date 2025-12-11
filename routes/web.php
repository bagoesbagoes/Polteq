<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Proposal;
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
// PUBLIC POST ROUTES (dibiarkan publik atau nanti bisa dipindah ke auth)
// ==========================

Route::get('/posts', function () {
    return view('posts', [
        'title' => 'Karya Usulan',
        'posts' => Post::filter(request(['search', 'category', 'author']))->latest()->get()
    ]);
});

Route::get('/posts/{post:slug}', function(Post $post) {
    return view('post', [
        'title' => 'Karya Usulan',
        'post' => $post
    ]);
});

Route::get('/authors/{user:username}', function(User $user){
    return view('posts', [
        'title' => count($user->posts).' Karya Usulan Oleh '. $user->name,
        'posts' => $user->posts
    ]);
});

Route::get('/categories/{category:slug}', function(Category $category){
    return view('posts', [
        'title' => 'Articles in: ' . $category->name,
        'posts' => $category->posts
    ]);
});

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
    Route::post('/signup', [RegisterController::class, 'register'])->name('signup.process');
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

    // Dashboard utama setelah login
    Route::get('/ManajemenProposalPenelitian', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Halaman biasa
    Route::get('/profile', function () {
        return view('profile', ['title' => 'Profile']);
    });

    Route::get('/upload', function () {
        return view('upload', ['title' => 'Upload']);
    });

    Route::get('/ManajemenLaporanPKM', function () {
        return view('ManajemenLaporanPKM', ['title' => 'Manajemen Laporan PKM']);
    });

    Route::get('/ManajemenProposalPKM', function () {
        return view('ManajemenProposalPKM', ['title' => 'Manajemen Proposal PKM']);
    });


    // ==========================
    // PUBLISHER
    // ==========================
    Route::middleware('role:publisher')->group(function () {
        Route::get('/publisher/dashboard', [PublisherController::class, 'dashboard']);
        Route::get('/publisher/upload', [PublisherController::class, 'create']);
        Route::post('/publisher/upload', [PublisherController::class, 'store']);
        Route::get('/publisher/proposal/{proposal}', [PublisherController::class, 'show']);
        Route::post('/publisher/proposal/{proposal}/revisi', [PublisherController::class, 'submitRevision']);
    });


    // ==========================
    // REVIEWER
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
    // ADMIN
    // ==========================
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/admin/reviewer/create', [AdminController::class, 'createReviewer']);
        Route::post('/admin/reviewer/create', [AdminController::class, 'storeReviewer']);
    });


    // ==========================
    // PROPOSAL CRUD (SEMUA ROLE YANG LOGIN)
    // ==========================
    Route::resource('proposals', ProposalController::class);
    Route::post('proposals/{proposal}/submit', [ProposalController::class, 'submit'])
        ->name('proposals.submit');
});



