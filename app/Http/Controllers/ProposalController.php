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
        $proposals = Auth::user()->proposals()->latest()->paginate(10);
        return view('proposals.index', [
            'title' => 'Proposal usulan',
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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_usulan' => 'required|mimes:pdf|max:10240',
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
        return view('proposals.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_usulan' => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_usulan')) {
            Storage::disk('public')->delete($proposal->file_usulan);
            $validated['file_usulan'] = $request->file('file_usulan')->store('proposals', 'public');
        }

        $proposal->update($validated);

        return redirect()->route('proposals.show', $proposal)->with('success', 'Proposal berhasil diupdate');
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
}