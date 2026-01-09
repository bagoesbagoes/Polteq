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
            // ->orWhere('status', 'under_review')
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

        if (!in_array($proposal->status, ['submitted', 'under_review'])) {
        return back()->with('error', 'Proposal ini tidak bisa direview karena statusnya: ' . $proposal->status);
    }

        $validated = $request->validate([
        'pendahuluan' => [
            'required',
            'integer',
            'min:0',
            'max:100'
        ],
        'tinjauan_pustaka' => [
            'required',
            'integer',
            'min:0',
            'max:100'
        ],
        'metodologi' => [
            'required',
            'integer',
            'min:0',
            'max:100'
        ],
        'kelayakan' => [
            'required',
            'integer',
            'min:0',
            'max:100'
        ],
        'recommendation' => 'required|in:setuju,tidak_setuju',
        'comment' => 'nullable|string|max:5000|min:10',
    ], [
        // Custom error messages
        'pendahuluan.max' => 'Skor Pendahuluan maksimal 100',
        'pendahuluan.min' => 'Skor Pendahuluan minimal 0',
        'tinjauan_pustaka.max' => 'Skor Tinjauan Pustaka maksimal 100',
        'tinjauan_pustaka.min' => 'Skor Tinjauan Pustaka minimal 0',
        'metodologi.max' => 'Skor Metodologi maksimal 100',
        'metodologi.min' => 'Skor Metodologi minimal 0',
        'kelayakan.max' => 'Skor Kelayakan maksimal 100',
        'kelayakan.min' => 'Skor Kelayakan minimal 0',
    ]);

        // Hitung total score (weighted average)
        $scores = [
            'pendahuluan' => $validated['pendahuluan'],
            'tinjauan_pustaka' => $validated['tinjauan_pustaka'],
            'metodologi' => $validated['metodologi'],
            'kelayakan' => $validated['kelayakan'],
        ];

        // Bobot masing-masing 25%
        $totalScore = (
            ($scores['pendahuluan'] * 0.25) +
            ($scores['tinjauan_pustaka'] * 0.25) +
            ($scores['metodologi'] * 0.25) +
            ($scores['kelayakan'] * 0.25)
        );

         $recommendation = $validated['recommendation']; // 'setuju' atau 'tidak_setuju'

        $statusMapping = [
        'setuju' => 'accepted',
        'tidak_setuju' => 'need_revision',
        ];

        // Mapping recommendation ke status
        $recommendationMapping = [
            'setuju' => 'accepted',
            'tidak_setuju' => 'need_revision',
        ];

        // Save review
        $review = Review::updateOrCreate(
            [
                'proposal_id' => $proposal->id,
                'reviewer_id' => Auth::id()
            ],
            [
                'scores' => $scores,
                'total_score' => $totalScore,
                'comment' => $validated['comment'],
                'recommendation' => $validated['recommendation'],
            ]
        );

        // Update proposal status
        $newStatus = $recommendationMapping[$validated['recommendation']];
        $proposal->update(['status' => $newStatus]);

        return redirect()->route('proposals.browse')
            ->with('success', 'Review berhasil disimpan. Status proposal diupdate ke: ' . $newStatus);
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