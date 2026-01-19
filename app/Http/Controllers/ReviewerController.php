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
        $reviewer = Auth::user();
        
        // Proposal yang bisa direview (status: submitted, belum direview oleh reviewer ini)
        $proposals = Proposal::where('status', 'submitted')
            ->whereDoesntHave('reviews', function($query) use ($reviewer) {
                $query->where('reviewer_id', $reviewer->id);
            })
            ->with('author')
            ->latest()
            ->paginate(10);
        
        // Riwayat review yang sudah dibuat reviewer ini
        $myReviews = Review::where('reviewer_id', $reviewer->id)
            ->with('proposal')
            ->latest()
            ->paginate(10);
        
        return view('reviewer.dashboard', [
            'proposals' => $proposals,
            'myReviews' => $myReviews
        ]);
    }
    
    // Form review proposal
    public function reviewForm(Proposal $proposal)
    {
        $reviewer = Auth::user();
        
        // Cek: Apakah proposal bisa direview?
        if ($proposal->status !== 'submitted') {
            return redirect()->back()->with('error', 'Proposal ini tidak dapat direview (status: ' . $proposal->status . ')');
        }
        
        // Cek: Apakah reviewer ini sudah pernah review?
        $existingReview = Review::where('proposal_id', $proposal->id)
            ->where('reviewer_id', $reviewer->id)
            ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah melakukan review untuk proposal ini');
        }
        
        return view('reviewer.review', [
            'proposal' => $proposal,
            'review' => null // Untuk form baru
        ]);
    }
    
    // Store review
    public function storeReview(Request $request, Proposal $proposal)
    {
        $reviewer = Auth::user();
        
        // Validasi input
        $validated = $request->validate([
            'pendahuluan' => 'required|integer|min:0|max:100',
            'tinjauan_pustaka' => 'required|integer|min:0|max:100',
            'metodologi' => 'required|integer|min:0|max:100',
            'kelayakan' => 'required|integer|min:0|max:100',
            'recommendation' => 'required|in:setuju,tidak_setuju',
            'comment' => 'nullable|string|max:5000',
        ]);
        
        // Hitung total score (bobot 25% untuk setiap kriteria)
        $totalScore = (
            ($validated['pendahuluan'] * 0.25) +
            ($validated['tinjauan_pustaka'] * 0.25) +
            ($validated['metodologi'] * 0.25) +
            ($validated['kelayakan'] * 0.25)
        );
        
        // Simpan review
        $review = Review::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'scores' => [
                'pendahuluan' => $validated['pendahuluan'],
                'tinjauan_pustaka' => $validated['tinjauan_pustaka'],
                'metodologi' => $validated['metodologi'],
                'kelayakan' => $validated['kelayakan'],
            ],
            'total_score' => $totalScore,
            'recommendation' => $validated['recommendation'],
            'comment' => $validated['comment'],
        ]);
        
        // Update status proposal berdasarkan rekomendasi
        // HANYA 2 KEMUNGKINAN: accepted atau need_revision
        if ($validated['recommendation'] === 'setuju') {
            $proposal->update(['status' => 'accepted']);
            $statusMessage = 'disetujui';
        } else {
            $proposal->update(['status' => 'need_revision']);
            $statusMessage = 'memerlukan revisi';
        }
        
        // 🎯 REDIRECT KE /proposals/browse dengan pesan yang lebih informatif
        return redirect()->route('proposals.browse')
            ->with('success', "Review berhasil disimpan! Proposal \"{$proposal->judul}\" {$statusMessage}.");
}
    
    // Show detail review
    public function showReview(Review $review)
    {
        // Authorization: Hanya reviewer yang buat review ini yang bisa lihat
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('reviewer.show-review', [
            'review' => $review->load('proposal.author')
        ]);
    }
    
    // Edit review (jika diperlukan)
    public function editReview(Review $review)
    {
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('reviewer.review', [
            'proposal' => $review->proposal,
            'review' => $review
        ]);
    }
    
    // Update review
    public function updateReview(Request $request, Review $review)
    {
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'pendahuluan' => 'required|integer|min:0|max:100',
            'tinjauan_pustaka' => 'required|integer|min:0|max:100',
            'metodologi' => 'required|integer|min:0|max:100',
            'kelayakan' => 'required|integer|min:0|max:100',
            'recommendation' => 'required|in:setuju,tidak_setuju',
            'comment' => 'nullable|string|max:5000',
        ]);
        
        $totalScore = (
            ($validated['pendahuluan'] * 0.25) +
            ($validated['tinjauan_pustaka'] * 0.25) +
            ($validated['metodologi'] * 0.25) +
            ($validated['kelayakan'] * 0.25)
        );
        
        $review->update([
            'scores' => [
                'pendahuluan' => $validated['pendahuluan'],
                'tinjauan_pustaka' => $validated['tinjauan_pustaka'],
                'metodologi' => $validated['metodologi'],
                'kelayakan' => $validated['kelayakan'],
            ],
            'total_score' => $totalScore,
            'recommendation' => $validated['recommendation'],
            'comment' => $validated['comment'],
        ]);
        
        // Update status proposal
        $proposal = $review->proposal;
        if ($validated['recommendation'] === 'setuju') {
            $proposal->update(['status' => 'accepted']);
        } else {
            $proposal->update(['status' => 'need_revision']);
        }
        
        return redirect()->route('reviewer.show-review', $review)
            ->with('success', 'Review berhasil diupdate');
    }
    
    // Delete review
    public function deleteReview(Review $review)
    {
        if ($review->reviewer_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $proposal = $review->proposal;
        $review->delete();
        
        // Kembalikan status proposal ke 'submitted'
        $proposal->update(['status' => 'submitted']);
        
        return redirect()->route('reviewer.dashboard')
            ->with('success', 'Review berhasil dihapus');
    }
}