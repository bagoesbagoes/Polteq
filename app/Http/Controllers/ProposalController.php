<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Review;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Services\SuratKerjaService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller  
{
    protected $suratKerjaService;

    public function __construct(SuratKerjaService $suratKerjaService)
    {
        $this->suratKerjaService = $suratKerjaService;
    }

    public function index()
    {
        $proposals = Auth::user()->proposals()
            ->whereNotIn('status', ['accepted', 'need_revision'])
            ->latest()
            ->paginate(10);
        
        return view('proposals.index', [
            'title' => 'Upload usulan baru',
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
            
        $title = 'Detail usulan : '; 

        return view('proposals.show', compact('proposal', 'title'));
    }

    public function edit(Proposal $proposal)
    {
        $this->authorize('update', $proposal);
        
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
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file_usulan' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        
        $proposal->judul = $validated['judul'];
        $proposal->deskripsi = $validated['deskripsi'];
        
        if ($request->hasFile('file_usulan')) {
            if ($proposal->file_usulan) {
                Storage::delete($proposal->file_usulan);
            }
            
            $proposal->file_usulan = $request->file('file_usulan')->store('proposals', 'public');
        }
        
        if ($proposal->status === 'need_revision') {
            $proposal->reviews()->delete();
            $proposal->status = 'submitted';
        }
        
        $proposal->save();
        
        $message = $proposal->status === 'submitted' 
            ? 'Revisi berhasil diupload dan dikirim untuk direview kembali!'
            : 'Proposal berhasil diupdate!';
        
        return redirect()->route('proposals.show', $proposal)
            ->with('success', $message);
    }

    public function destroy(Proposal $proposal)
    {
        $this->authorize('delete', $proposal);

        if ($proposal->file_usulan) {
            Storage::disk('public')->delete($proposal->file_usulan);
        }

        $proposal->delete();

        if (Auth::user()->role ==='admin') {
            return redirect()
            ->route('proposals.browse')
            ->with('success', 'usulan berhasil dihapus');
        } else { 
            return redirect()
            ->route('proposals.index')
            ->with ('success', 'usulan berhasil dihapus');
        }
    }

    public function submit(Proposal $proposal)
    {
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        if ($proposal->status !== 'draft') {
            return redirect()->back()->with('error', 'Proposal ini sudah disubmit sebelumnya');
        }
        
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

    public function downloadSuratKerja(Proposal $proposal, Request $request)
    {
        // 1. AUTHORIZATION CHECK
        
        if ($proposal->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh surat kerja ini.');
        }
        
        if ($proposal->status !== 'accepted') {
            return redirect()
                ->route('proposals.show', $proposal)
                ->with('error', 'Surat kerja hanya tersedia untuk usulan yang sudah disetujui.');
        }
        
        // 2. PERIOD CHECK
        
        $today = Carbon::now('Asia/Jakarta');
        $currentYear = $today->year;
        
        $startDate = Carbon::create($currentYear, 7, 1, 0, 0, 0, 'Asia/Jakarta');
        $endDate = Carbon::create($currentYear, 9, 31, 00, 00, 00, 'Asia/Jakarta');
        
        if (!$today->between($startDate, $endDate)) {
            if ($today->lt($startDate)) {
                $nextPeriod = '1 Juli - 1 September ' . $currentYear;
            } else {
                $nextPeriod = '1 Juli - 1 September ' . ($currentYear + 1);
            }
            
            return redirect()
                ->route('proposals.show', $proposal)
                ->with('error', 'Download surat tugas hanya dapat dilakukan pada periode 1 Juli - 1 September. Periode download berikutnya: ' . $nextPeriod);
        }

        // 3. PREPARE DATA & GENERATE
        
        $data = $this->suratKerjaService->prepareData($proposal);
        $format = $request->get('format', 'pdf');
        
        if ($format === 'docx') {
            return $this->suratKerjaService->generateDocx($proposal, $data);
        };
    }

}