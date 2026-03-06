<?php

namespace App\Http\Controllers;

use App\Models\PkmProposal;
use App\Services\SuratKerjaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PkmProposalController extends Controller
{
    /**
     * Display listing (untuk publisher)
     */
    public function index()
    {
        $user = Auth::user();

        // Publisher: tampilkan semua PKM miliknya
        if ($user->role === 'publisher') {
            $pkms = PkmProposal::where('user_id', $user->id)
                ->latest()
                ->paginate(12);
            
            return view('pkm.index', [
                'title' => 'PKM Saya',
                'pkms' => $pkms
            ]);
        }
        
        // Admin & Reviewer: redirect ke browse
        return redirect()->route('pkm.browse');
    }

    /**
     * Browse PKM (untuk admin & reviewer)
     */
    public function browse()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            $pkms = PkmProposal::with('author')
                ->whereNotIn('status', ['draft'])
                ->latest()
                ->paginate(12);
        } else {
            // Reviewer
            $pkms = PkmProposal::with('author')
                ->where('status', 'submitted')
                ->latest()
                ->paginate(12);
        }
        
        return view('pkm.browse', [
            'title' => 'Browse Usulan PKM',
            'pkms' => $pkms
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('pkm.create', [
            'title' => 'Buat Usulan PKM Baru'
        ]);
    }

    /**
     * Store new PKM
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|integer|min:' . date('Y'),
            'anggota_tim' => 'nullable|array',
            'abstrak' => 'required|string|min:50',
            'file_usulan' => 'required|file|mimes:pdf|max:10240',
        ], [
            'file_usulan.required' => 'File usulan wajib diupload',
            'file_usulan.mimes' => 'File harus berformat PDF',
            'file_usulan.max' => 'Ukuran file maksimal 10MB',
            'abstrak.min' => 'Abstrak minimal 50 karakter',
        ]);

        // Upload file
        $file = $request->file('file_usulan');
        $filePath = $file->store('pkm_proposals', 'public');
        $fileSize = round($file->getSize() / 1024, 2);

        // Create PKM
        $pkm = PkmProposal::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'tahun_pelaksanaan' => $validated['tahun_pelaksanaan'],
            'anggota_tim' => $validated['anggota_tim'] ?? [],
            'abstrak' => $validated['abstrak'],
            'file_usulan' => $filePath,
            'file_size' => $fileSize,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('pkm.show', $pkm)
            ->with('success', 'Usulan PKM berhasil dibuat! Jangan lupa submit untuk direview.');
    }

    /**
     * Show PKM detail
     */
    public function show(PkmProposal $pkm)
    {
        $pkm->load('author', 'reviews.reviewer');
        
        return view('pkm.show', [
            'title' => 'Detail Usulan PKM',
            'pkm' => $pkm
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(PkmProposal $pkm)
    {
        // hanya publiserh dan admin yang dapat meng-edit usulan PKM
        if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($pkm->status !== 'draft' && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Hanya PKM dengan status draft yang bisa diedit');
        }

        $editableStatuses = ['draft', 'need_revision'];

        if (!in_array($pkm->status, $editableStatuses) && Auth::user()->role !== 'admin') {
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('error', 'Hanya PKM dengan status draft atau perlu revisi yang dapat diedit. ');
        }

        return view('pkm.edit', [
            'title' => $pkm->status === 'need_revision' ? 'Revisi usulan PKM' : 'Edit usulan PKM',
            'pkm' => $pkm
        ]);
    }

    public function showRevisionForm(PkmProposal $pkm)
    {
        if ($pkm->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan revisi pkm ini');
        }

        if ($pkm->status !== 'need_revision') {
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('error', 'Hanya pkm dengan status "need_revision" yang dapat melakukan revisi.');
        }   

        $pkm->load('reviews.reviewer');

        return view ('pkm.revisi', [
            'title' => 'Revisi Usulan PKM',
            'pkm' => $pkm
        ]);
    }


    /**
     * Update PKM
     */
    public function update(Request $request, PkmProposal $pkm)
    {
        if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|integer|min:' . date('Y'),
            'anggota_tim' => 'nullable|array',
            'abstrak' => 'required|string|min:100',
            'file_usulan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Update file if uploaded
        if ($request->hasFile('file_usulan')) {
            Storage::disk('public')->delete($pkm->file_usulan);
            
            $file = $request->file('file_usulan');
            $filePath = $file->store('pkm_proposals', 'public');
            $fileSize = round($file->getSize() / 1024, 2);
            
            $validated['file_usulan'] = $filePath;
            $validated['file_size'] = $fileSize;
        }

        // AUTO-SUBMIT LOGIC FOR REVISION
        $wasRevision = $pkm->status === 'need_revision';
        
        if ($wasRevision) {
            // Delete old reviews (optional)
            $pkm->reviews()->delete();
            
            // Change status to submitted
            $validated['status'] = 'submitted';
            $validated['submitted_at'] = now();
            $validated['revision_notes'] = null;
        }

        $pkm->update($validated);

        // Success message based on action
        if ($wasRevision) {
            $message = 'Revisi berhasil disimpan dan PKM telah disubmit ulang untuk direview!';
            // ← REDIRECT KE REVISIONS LIST
            return redirect()
                ->route('pkm.revisions')
                ->with('success', $message);
        } else {
            $message = 'Usulan PKM berhasil diupdate';
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('success', $message);
        }
    }

    /**
     * Delete PKM
     */
    public function destroy(PkmProposal $pkm)
    {
        // Authorization: Only owner or admin
        if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus PKM ini.');
        }

        // Status restriction: Publisher can ONLY delete draft
        if (Auth::user()->role === 'publisher' && $pkm->status !== 'draft') {
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('error', 'Hanya PKM dengan status "draft" yang dapat dihapus. PKM yang sudah disubmit tidak dapat dihapus lagi.');
        }

        // Store info for success message
        $judulPkm = $pkm->judul;

        // Delete file from storage
        if ($pkm->file_usulan) {
            Storage::disk('public')->delete($pkm->file_usulan);
        }

        // Delete PKM (cascade delete reviews via model relationship)
        $pkm->delete();

        // Redirect based on role
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route('admin.pkm')
                ->with('success', 'Usulan PKM "' . $judulPkm . '" berhasil dihapus.');
        } else {
            return redirect()
                ->route('pkm.index')
                ->with('success', 'Usulan PKM "' . $judulPkm . '" berhasil dihapus.');
        }
    }

    /**
     * Submit PKM untuk direview
     */
    public function submit(PkmProposal $pkm)
    {
        if ($pkm->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pkm->status !== 'draft' && $pkm->status !== 'need_revision') {
            return back()->with('error', 'PKM ini sudah disubmit sebelumnya');
        }

        $pkm->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'revision_notes' => null,
        ]);

        return back()->with('success', 'Usulan PKM berhasil disubmit! Menunggu review dari reviewer.');
    }

    /**
     * PKM Disetujui (Publisher only)
     */
    public function accepted()
    {
        $pkms = PkmProposal::where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->latest()
            ->paginate(12);

        return view('pkm.accepted', [
            'title' => 'PKM Disetujui',
            'pkms' => $pkms
        ]);
    }

    /**
     * PKM Revisi (Publisher only)
     */
    public function revisions()
    {
        $pkms = PkmProposal::where('user_id', Auth::id())
            ->where('status', 'need_revision')
            ->latest()
            ->paginate(12);

        return view('pkm.revisions', [
            'title' => 'PKM Perlu Revisi',
            'pkms' => $pkms
        ]);
    }

    /**
     * Download file PKM
     */
    public function download(PkmProposal $pkm)
    {
        $filePath = storage_path('app/public/' . $pkm->file_usulan);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }
        
        return response()->download($filePath);
    }

    /**
     * Download Surat Tugas PKM
     */
    public function downloadSuratTugas(PkmProposal $pkm)
    {
        // Authorization check
        if ($pkm->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh surat tugas ini.');
        }
        
        // Status check
        if ($pkm->status !== 'accepted') {
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('error', 'Surat tugas hanya tersedia untuk PKM yang sudah disetujui.');
        }
        
        // PERIOD CHECK (OPTIONAL)
        $today = Carbon::now('Asia/Jakarta');
        $currentYear = $today->year;
        
        $startDate = Carbon::create($currentYear, 7, 1, 0, 0, 0, 'Asia/Jakarta');
        $endDate = Carbon::create($currentYear, 9, 19, 23, 59, 59, 'Asia/Jakarta');
        
        if (!$today->between($startDate, $endDate)) {
            if ($today->lt($startDate)) {
                $nextPeriod = '1 Juli - 1 September ' . $currentYear;
            } else {
                $nextPeriod = '1 Juli - 1 September ' . ($currentYear + 1);
            }
            
            return redirect()
                ->route('pkm.show', $pkm)
                ->with('error', 'Download surat tugas hanya dapat dilakukan pada periode 1 Juli - 1 September. Periode download berikutnya: ' . $nextPeriod);
        }
        
        // Generate
        $suratKerjaService = app(SuratKerjaService::class);
        $data = $suratKerjaService->preparePkmData($pkm);
        
        return $suratKerjaService->generatePkmDocx($pkm, $data);
    }
}