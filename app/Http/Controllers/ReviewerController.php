<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Hanya reviewer yang bisa akses
            if (Auth::user()->role !== 'reviewer') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    // Dashboard - list proposals untuk di-review
    public function dashboard()
    {
        $proposals = Proposal::where('status', 'submitted')
            ->orWhere('status', 'under_review')
            ->latest()
            ->paginate(10);
            
        $myReviews = Auth::user()->reviews()->latest()->paginate(5);
        
        return view('reviewer.dashboard', compact('proposals', 'myReviews'));
    }

    // Form review (create/edit)
    public function reviewForm(Proposal $proposal)
    {
        $this->authorize('review', $proposal);
        
        // Cek apakah sudah ada review dari reviewer ini
        $review = $proposal->reviews()->where('reviewer_id', Auth::id())->first();
        
        return view('reviewer.review', compact('proposal', 'review'));
    }

    // Store atau update review
    public function storeReview(Request $request, Proposal $proposal)
    {
        $this->authorize('review', $proposal);

        $validated = $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
        ]);

        // Save atau update review
        $review = Review::updateOrCreate(
            [
                'proposal_id' => $proposal->id,
                'reviewer_id' => Auth::id()
            ],
            [
                'comment' => $validated['catatan'],
                'recommendation' => $validated['recommendation'],
            ]
        );

        // Update proposal status menjadi under_review
        if ($proposal->status === 'submitted') {
            $proposal->update(['status' => 'under_review']);
        }

        return redirect()->route('reviewer.dashboard')->with('success', 'Hasil review tersimpan.');
    }

    // List reviews dari reviewer ini
    public function myReviews()
    {
        $reviews = Auth::user()->reviews()->with('proposal')->latest()->paginate(10);
        return view('reviewer.my-reviews', compact('reviews'));
    }

    // Show detail review
    public function showReview(Review $review)
    {
        $this->authorize('view', $review);
        return view('reviewer.show-review', compact('review'));
    }

    // Edit review
    public function editReview(Review $review)
    {
        $this->authorize('update', $review);
        $proposal = $review->proposal;
        
        return view('reviewer.edit-review', compact('review', 'proposal'));
    }

    // Update review
    public function updateReview(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string',
            'recommendation' => 'required|in:accept,minor_revision,major_revision,reject',
        ]);

        $review->update([
            'comment' => $validated['catatan'],
            'recommendation' => $validated['recommendation'],
        ]);

        return redirect()->route('reviewer.my-reviews')->with('success', 'Review berhasil diupdate');
    }

    // Delete review
    public function deleteReview(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus');
    }
}