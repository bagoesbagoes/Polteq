<?php

namespace App\Http\Controllers;

use App\Models\PkmProposal;
use App\Models\PkmReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PkmReviewerController extends Controller
{
    // Daftar PKM untuk direview

    public function index()
    {
        $pkms = PkmProposal::with('author', 'reviews')
            ->where('status', 'submitted')
            ->latest()
            ->paginate(12);

        return view('reviewer.pkm.index', [
            'title' => 'Daftar PKM untuk review',
            'pkms' =>$pkms
        ]);
    }

    // menampilkan form review

    public function reviewForm(PkmProposal $pkm)
    {
        $existingReview = PkmReview::where('pkm_proposal_id', $pkm->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()
                ->route('reviewer.pkm-show-reviewer', $existingReview)
                ->with('info', 'Anda sudah melakukan review untuk PKM ini. Anda bisa edit jika diperlukan');
        }

        return view('reviewer.pkm.review-form', [
            'title' => 'Reviewer PKM',
            'pkm' => $pkm->load('author')
        ]);
    }

    // Menyimpan review baru

    public function storeReview(Request $request, PkmProposal $pkm)
    {
        // Check if already reviewed
        $existingReview = PkmReview::where('pkm_proposal_id', $pkm->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah melakukan review untuk PKM ini.');
        }

        $validated = $request->validate([
            'perumusan_masalah' => 'required|integer|min:0|max:100',
            'peluang_luaran' => 'required|integer|min:0|max:100',
            'metode_pkm' => 'required|integer|min:0|max:100',
            'tinjauan_pustaka' => 'required|integer|min:0|max:100',
            'kelayakan_pkm' => 'required|integer|min:0|max:100',
            'comments' => 'nullable|string|max:5000',
            'recommendation' => 'required|in:setuju,tidak_setuju',
        ], [
            'perumusuan_masalah.required' => 'Score perumusuan masalah wajib diisi',
            'peluang_luaran.required' => 'Score peluang luaran wajib diisi',
            'metode_pkm.required' => 'Score metode PKM wajib diisi',
            'tinjauan_pustaka.required' => 'Score tinjauan pustaka wajib diisi',
            'kelayakan_pkm.required' => 'Score kelayakan PKM wajib diisi',
            'recommendation.required' => 'Rekomendasi wajib dipilih',
        ]);

        $scores = [
            'perumusan_masalah' => $validated['perumusan_masalah'],
            'peluang_luaran' => $validated['peluang_luaran'],
            'metode_pkm' => $validated['metode_pkm'],
            'tinjauan_pustaka' => $validated['tinjauan_pustaka'],
            'kelayakan_pkm' => $validated['kelayakan_pkm'],
        ];

        // Create review
        $review = PkmReview::create([
            'pkm_proposal_id' => $pkm->id,
            'reviewer_id' => Auth::id(),
            'scores' => $scores,
            'total_score' => round($totalScore, 2),
            'score' => round($totalScore), // Legacy support
            'comments' => $validated['comment'],
            'recommendation' => $validated['recommendation'],
        ]);

        // Update PKM status based on recommendation
        if ($validated['recommendation'] === 'accept') {
            $pkm->update([
                'status' => 'accepted',
                'revision_notes' => null,
            ]);
        } elseif ($validated['recommendation'] === 'revise') {
            $pkm->update([
                'status' => 'need_revision',
                'revision_notes' => $validated['comments'] ?? 'PKM perlu diperbaiki sesuai catatan reviewer.',
            ]);
        }

        return redirect()
            ->route('reviewer.pkm-show-review', $review)
            ->with('success', 'Review berhasil disimpan! PKM telah di-update ke status: ' . ($validated['recommendation'] === 'setuju' ? 'Accepted' : 'Need Revision'));
    }

    public function showReview(PkmReview $review)
    {
        // Only reviewer who created this review can see it
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $review->load('pkmProposal.author', 'reviewer');

        return view('reviewer.pkm.show-review', [
            'title' => 'Detail Review PKM',
            'review' => $review
        ]);
    }

    public function editReview(PkmReview $review)
    {
        // Only reviewer who created this review can edit it
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $review->load('pkmProposal.author');

        return view('reviewer.pkm.edit-review', [
            'title' => 'Edit Review PKM',
            'review' => $review
        ]);
    }

    public function updateReview(Request $request, PkmReview $review)
    {
        // Only reviewer who created this review can update it
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'perumusan_masalah' => 'required|integer|min:0|max:100',
            'peluang_luaran' => 'required|integer|min:0|max:100',
            'metode_pkm' => 'required|integer|min:0|max:100',
            'tinjauan_pustaka' => 'required|integer|min:0|max:100',
            'kelayakan_pkm' => 'required|integer|min:0|max:100',
            'recommendation' => 'required|in:setuju,tidak_setuju',
            'comment' => 'nullable|string|max:5000',
        ]);

        // perhitungan score
        $totalScore = 
            ($validated['perumusan_masalah'] * 0.25) +
            ($validated['peluang_luaran'] * 0.25) +
            ($validated['metode_pkm'] * 0.25) +
            ($validated['tinjauan_pustaka'] * 0.15) +
            ($validated['kelayakan_pkm'] * 0.10);
        
        $scores = [
            'perumusan_masalah' => $validated['perumusan_masalah'],
            'peluang_luaran' => $validated['peluang_luaran'],
            'metode_pkm' => $validated['metode_pkm'],
            'tinjauan_pustaka' => $validated['tinjauan_pustaka'],
            'kelayakan_pkm' => $validated['kelayakan_pkm'],
        ];

        // Update review
        $review->update([
            'scores' => $scores,
            'total_score' => round($totalScore, 2),
            'score' => round($totalScore),
            'comments' => $validated['comment'],
            'recommendation' => $validated['recommendation']
        ]);

        // Update PKM status if recommendation changed
        $pkm = $review->pkmProposal;
        
        if ($validated['recommendation'] === 'setuju') {
            $pkm->update([
                'status' => 'accepted',
                'revision_notes' => null,
            ]);
        } else {
            $pkm->update([
                'status' => 'need_revision',
                'revision_notes' => $validated['comments'] ?? 'PKM perlu diperbaiki sesuai dengan catatan reviewer.'
            ]);
        }

        return redirect()
            ->route('reviewer.pkm-show-review', $review)
            ->with('success', 'Review berhasil diupdate!');
    }

    public function deleteReview(PkmReview $review)
    {
        // Only reviewer who created this review can delete it
        if ($review->reviewer_id !== Auth::id()) {
            abort(403);
        }

        $pkm = $review->pkmProposal;
        
        // Revert PKM status to submitted if this was the only review
        if ($pkm->reviews()->count() === 1) {
            $pkm->update([
                'status' => 'submitted',
                'revision_notes' => null,
            ]);
        }

        $review->delete();

        return redirect()
            ->route('reviewer.pkm')
            ->with('success', 'Review berhasil dihapus');
    }

}
