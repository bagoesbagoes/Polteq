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
            'title' => 'Proposal Usulan',
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
            'title' => 'Buat Proposal Baru'
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
            'judul.min' => 'Judul proposal minimal 5 karakter',
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

        return redirect()->route('proposals.show', $proposal)->with('success', 'Proposal berhasil dibuat');
    }

    public function show(Proposal $proposal)
    {
        $this->authorize('view', $proposal);
            
        $title = 'Detail Proposal : '; 

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
                ->with('error', 'Proposal dengan status "' . $proposal->status . '" tidak dapat diedit. Hanya proposal dengan status "draft" atau "need_revision" yang dapat diedit.');
        }
        
        return view('proposals.edit', [
            'proposal' => $proposal,
            'title' => 'Edit Proposal'
        ]);
    }

    public function update(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        // Hanya draft dan need_revision yang boleh diedit
        $editableStatuses = ['draft', 'need_revision'];
        
        if (!in_array($proposal->status, $editableStatuses)) {
            return back()->with('error', 'Proposal dengan status "' . $proposal->status . '" tidak dapat diedit.');
        }

        // Untuk need_revision, file wajib diupload
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_usulan' => $proposal->status === 'need_revision' 
                ? 'required|mimes:pdf|max:10240'  // Wajib untuk revisi
                : 'nullable|mimes:pdf|max:10240', // Opsional untuk draft
        ], [
            'file_usulan.required' => 'File revisi wajib diupload untuk proposal yang perlu revisi.',
        ]);

        // Update judul dan deskripsi
        $proposal->judul = $validated['judul'];
        $proposal->deskripsi = $validated['deskripsi'];

        // Handle file upload
        if ($request->hasFile('file_usulan')) {
            // Hapus file lama
            Storage::disk('public')->delete($proposal->file_usulan);
            
            // Upload file baru
            $proposal->file_usulan = $request->file('file_usulan')->store('proposals', 'public');
        }

        // Jika status need_revision, hapus review lama agar reviewer bisa review lagi
        if ($proposal->status === 'need_revision') {
            // Hapus semua review terkait proposal ini
            $proposal->reviews()->delete();
            
            $proposal->status = 'submitted';
            $successMessage = 'Revisi berhasil diupload dan dikirim untuk review ulang. Review sebelumnya telah dihapus.';
        } else {
            $successMessage = 'Proposal berhasil diupdate';
        }

        $proposal->save();

        return redirect()
            ->route('proposals.show', $proposal)
            ->with('success', $successMessage);
    }

    public function destroy(Proposal $proposal)
    {
        $this->authorize('delete', $proposal);

        Storage::disk('public')->delete($proposal->file_usulan);
        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'Proposal berhasil dihapus');
    }

    public function submit(Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        if ($proposal->status !== 'draft') {
            return back()->with('error', 'Hanya draft proposal yang bisa di-submit');
        }

        $proposal->update(['status' => 'submitted']);

        return back()->with('success', 'Proposal berhasil di-submit untuk review');
    }

    public function browseForReviewer()
    {
        $this->authorize('review', Proposal::class);

        $proposals = Proposal::where('status', 'submitted')
            ->latest()
            ->paginate(10);

        return view('proposals.browse', [
            'title' => 'Proposal Menunggu Review',
            'proposals' => $proposals,
        ]);
    }
}