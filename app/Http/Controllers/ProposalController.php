<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProposalController extends Controller  
{

    public function index()
    {
        // Exclude proposals yang sudah accepted
        $proposals = Auth::user()->proposals()
            ->whereNotIn('status', ['accepted', 'need_revision'])
            ->latest()
            ->paginate(10);
        
        return view('proposals.index', [
            'title' => 'daftar pengajuan Usulan',
            'proposals' => $proposals,
        ]);
    }

    public function accepted()
    {
        $proposals = Auth::user()->proposals()
            ->where('status', 'accepted')
            ->latest()
            ->paginate(10);
        
        return view('proposals.index', [
            'title' => 'Usulan Disetujui',
            'proposals' => $proposals,
        ]);
    }

    public function revisions()
    {
        $proposals = Auth::user()->proposals()
            ->where('status', 'need_revision')
            ->latest()
            ->paginate(10);
        
        return view('proposals.index', [
            'title' => 'Revisi Usulan',
            'proposals' => $proposals,
        ]);
    }

    public function create()
    {
        return view('proposals.create', [
            'title' => 'Buat usulan Baru'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255|min:5|regex:/^[a-zA-Z0-9\s\-\.,:()]+$/u',
            'deskripsi' => 'required|string|min:50|max:5000',
            'file_usulan' => 'required|mimes:pdf|max:10240|mimetypes:application/pdf',
        ], [
            // Custom error messages
            'judul.min' => 'Judul usulan minimal 5 karakter',
            'judul.regex' => 'Judul hanya boleh mengandung huruf, angka, dan tanda baca standar',
            'deskripsi.min' => 'Deskripsi/abstrak minimal 50 karakter',
            'file_usulan.mimetypes' => 'File harus berupa PDF yang valid',
        ]);

        $filePath = $request->file('file_usulan')->store('proposals', 'public');

        $proposal = Auth::user()->proposals()->create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'file_usulan' => $filePath,
            'status' => 'draft',
        ]);

        return redirect()->route('proposals.show', $proposal)->with('success', 'usulan berhasil dibuat');
    }

    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);
            
        $title = 'Detil usulan : '; 

        return view('proposals.show', compact('proposal', 'title'));
    }

    public function edit(Proposal $proposal)
    {
        $this->authorize('update', $proposal);
        
        // VALIDASI: Cek apakah status boleh diedit
        $editableStatuses = ['draft', 'need_revision'];
        
        if (!in_array($proposal->status, $editableStatuses)) {
            return redirect()
                ->route('proposals.show', $proposal)
                ->with('error', 'usulan dengan status "' . $proposal->status . '" tidak dapat diedit. Hanya usulan dengan status "draft" atau "need_revision" yang dapat diedit.');
        }
        
        return view('proposals.edit', [
            'proposal' => $proposal,
            'title' => 'Edit usulan'
        ]);
    }

    public function update(Request $request, Proposal $proposal)
    {
        // Authorization
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_usulan' => 'nullable|file|mimes:pdf|max:10240', // 10MB
        ]);
        
        // Update data
        $proposal->judul = $validated['judul'];
        $proposal->deskripsi = $validated['deskripsi'];
        
        // Upload file baru jika ada
        if ($request->hasFile('file_usulan')) {
            // Hapus file lama
            if ($proposal->file_usulan) {
                Storage::delete($proposal->file_usulan);
            }
            
            $proposal->file_usulan = $request->file('file_usulan')->store('proposals', 'public');
        }
        
        // Jika status 'need_revision', kembalikan ke 'submitted' untuk review ulang
        if ($proposal->status === 'need_revision') {
            // HAPUS SEMUA REVIEW LAMA
            $proposal->reviews()->delete();
            
            // Set status kembali ke submitted
            $proposal->status = 'submitted';
        }
        
        $proposal->save();
        
        $message = $proposal->status === 'submitted' 
            ? 'Revisi berhasil diupload dan dikirim untuk review ulang!'
            : 'Proposal berhasil diupdate!';
        
        return redirect()->route('proposals.show', $proposal)
            ->with('success', $message);
    }

    public function destroy(Proposal $proposal)
    {
        $this->authorize('delete', $proposal);

        Storage::disk('public')->delete($proposal->file_usulan);
        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'usulan berhasil dihapus');
    }

    public function submit(Proposal $proposal)
    {
        // Authorization: Hanya pemilik proposal
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Cek: Proposal harus status 'draft'
        if ($proposal->status !== 'draft') {
            return redirect()->back()->with('error', 'Proposal ini sudah disubmit sebelumnya');
        }
        
        // Update status jadi 'submitted'
        $proposal->update(['status' => 'submitted']);
        
        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal berhasil disubmit untuk direview!');
    }

    public function browseForReviewer()
    {
        $this->authorize('review', Proposal::class);

        $proposals = Proposal::where('status', 'submitted')
            ->latest()
            ->paginate(10);

        return view('proposals.browse', [
            'title' => 'usulan Menunggu Review',
            'proposals' => $proposals,
        ]);
    }
}