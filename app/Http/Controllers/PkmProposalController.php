<?php

namespace App\Http\Controllers;

use App\Models\PkmProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PkmProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $pkms = PkmProposal::with('author')
                ->whereNotin('status', ['draft'])
                ->latest()
                ->paginate(12);
        } elseif ($user->role === 'reviewer'){
            $pkms = PkmProposal::with('author')
                ->where('status', 'submitted')
                ->lates()
                ->paginate(12);
        } else {
            $pkms = PkmProposal::where('user_id', $user->id)
                ->latest()
                ->paginate(12);
        }

        return view('pkm.browse', [
            'tittle' => 'Daftar Usulan PKM',
            'pkms' => $pkms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pkm.create', [
            'tittle' => 'Buat PKM baru'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|integer|min:2020|max:2030',
            'sumber_dana' => 'required|in:DIPA,Non_DIPA',
            'kategori_pkm' => 'required|string|max:255',
            'kelompok_riset' => 'nullable|string|max:255',
            'anggota_tim' => 'nullable|array',
            'abstrak' => 'required|string|min:100',
            'file_usulan' => 'required|file|mimes:pdf|max:102040',
        ],[
            'file_usulan.required' => 'File usulan wajib diunggah',
            'file_usulan.mimes' => 'File harus berfomat PDF',
            'file_usulan.max' => 'Ukuran file maksimal 10MB',
            'abstrak.min' => 'Abstrak minimal 100 karakter',
        ]);

        // Logika Upload File
        $file = $request->file('file_usulan');
        $filePath = $file->store('pkm_proposals', 'public');
        $fileSize = round($file->getSize() / 1024, 2);

        // Create PKM
        $pkm = PkmProposal::create([
            'user_id' => Auth::id(),
            'judul' => $validated['judul'],
            'tahun_pelaksanaan' => $validated['tahun_pelaksanaan'],
            'sumber_dana' => $validated['sumber_dana'],
            'kategori_pkm' => $validated['kategori_pkm'],
             'kelompok_riset' => $validated['kelompok_riset'],
            'anggota_tim' => $validated['anggota_tim'] ?? [],
            'abstrak' => $validated['abstrak'],
            'file_usulan' => $filePath,
            'file_size' => $fileSize,
            'status' => 'draft',
        ]);
        return redirect()
            ->route('pkm.show', $pkm)
            ->with('success', 'Usulan PKM berhasil dibuat. silahkan finalisasi dengan menekan tombol submit');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pkm->load('author', 'reviews.reviewer');
        
        return view('pkm.show', [
            'title' => 'Detail Usulan PKM',
            'pkm' => $pkm
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Only publisher can edit their own draft, or admin can edit any
        if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($pkm->status !== 'draft' && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Hanya PKM dengan status draft yang bisa diedit');
        }

        return view('pkm.edit', [
            'title' => 'Edit Usulan PKM',
            'pkm' => $pkm
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tahun_pelaksanaan' => 'required|integer|min:2020|max:2030',
            'sumber_dana' => 'required|in:DIPA,Non-DIPA',
            'kategori_pkm' => 'required|string|max:255',
            'kelompok_riset' => 'nullable|string|max:255',
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

        $pkm->update($validated);

        return redirect()
            ->route('pkm.show', $pkm)
            ->with('success', 'Usulan PKM berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($pkm->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        Storage::disk('public')->delete($pkm->file_usulan);
        $pkm->delete();

        // Redirect berdasarkan role
        if (Auth::user()->role === 'admin') {
            return redirect()->route('pkm.browse')->with('success', 'Usulan PKM berhasil dihapus');
        } else {
            return redirect()->route('pkm.index')->with('success', 'Usulan PKM berhasil dihapus');
        }
    }

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
            'revision_notes' => null, // Clear previous revision notes
        ]);

        return back()->with('success', 'Usulan PKM berhasil disubmit! Menunggu review dari reviewer.');
    }

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

}
