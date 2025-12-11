<?php
namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function dashboard(Request $request)
    {
        $proposals = $request->user()->proposals()->latest()->get();
        return view('publisher.dashboard', compact('proposals'));
    }

    public function create()
    {
        return view('publisher.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_usulan' => 'required|file|mimes:pdf|max:10240',
        ]);

        $file = $request->file('file_usulan');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/proposals', $filename);

        $proposal = Proposal::create([
            'user_id' => $request->user()->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file_usulan' => 'proposals/' . $filename, // relative to storage/app/public
            'status' => 'menunggu',
        ]);

        return redirect()->route('publisher.dashboard')->with('success', 'Proposal berhasil diunggah.');
    }

    public function show(Proposal $proposal)
    {
        // ensure only owner publisher or admin/reviewer can access view — or middleware handles routes
        return view('publisher.show', compact('proposal'));
    }

    public function submitRevision(Request $request, Proposal $proposal)
    {
        // ensure current user is owner
        if ($request->user()->id !== $proposal->user_id) abort(403);

        $validated = $request->validate([
            'file_revisi' => 'required|file|mimes:pdf|max:10240',
            'note' => 'nullable|string',
        ]);

        $file = $request->file('file_revisi');
        $filename = time() . '_rev_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/proposals', $filename);

        // update proposal: set file_revisi and status to 'menunggu' or 'revisi' depending desired flow.
        $proposal->update([
            'file_revisi' => 'proposals/' . $filename,
            'status' => 'menunggu', // or 'revisi' until reviewer checks; choose consistent approach
        ]);

        return back()->with('success', 'Revisi berhasil diunggah. Menunggu penilaian reviewer.');
    }
}
