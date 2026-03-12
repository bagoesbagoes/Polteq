<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\PkmProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PkmReportController extends Controller
{
    /**
     * Display list of PKM reports by type (for Publisher)
     */
    public function index($type = 'laporan_akhir')
    {
        // Validasi type
        if (!in_array($type, ['laporan_akhir', 'luaran'])) {
            abort(404);
        }
        
        // Publisher hanya lihat laporan PKM sendiri
        $reports = Report::where('user_id', Auth::id())
            ->where('type', $type)
            ->where(function($query) {
                // Show PKM reports: either has pkm_proposal_id OR standalone (no proposal_id)
                $query->whereNotNull('pkm_proposal_id')
                    ->orWhereNull('proposal_id');
            })  
            ->with('pkmProposal:id,judul')     
            ->latest()
            ->paginate(10);
        
        $title = $type === 'laporan_akhir' ? 'Daftar Laporan Akhir PKM' : 'Daftar Luaran PKM';
        
        return view('pkm-reports.index', [
            'title' => $title,
            'reports' => $reports,
            'type' => $type,
        ]);
    }

    /**
     * Show create form
     */
    public function create($type = 'laporan_akhir')
    {
        // Validasi type
        if (!in_array($type, ['laporan_akhir', 'luaran'])) {
            abort(404);
        }
        
        // Get accepted PKM proposals yang belum punya laporan/luaran
        $pkmProposals = PkmProposal::where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->whereDoesntHave('reports', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->get();
        
        // HANYA untuk laporan_akhir, wajib ada PKM proposal
        if ($type === 'laporan_akhir' && $pkmProposals->isEmpty()) {
            return redirect()
                ->route('pkm-reports.laporan-akhir')
                ->with('error', 'Tidak ada usulan PKM yang tersedia untuk upload laporan akhir. Semua usulan PKM yang disetujui sudah memiliki laporan akhir.');
        }
        
        $title = $type === 'laporan_akhir' ? 'Upload Laporan Akhir PKM' : 'Upload Luaran PKM';
        
        return view('pkm-reports.create', [
            'title' => $title,
            'type' => $type,
            'pkmProposals' => $pkmProposals,
        ]);
    }

    /**
     * Store new PKM report
     */
    public function store(Request $request, $type)
    {
        // Validasi type
        if (!in_array($type, ['laporan_akhir', 'luaran'])) {
            abort(404);
        }
        
        // Validasi berbeda untuk laporan_akhir vs luaran
        if ($type === 'laporan_akhir') {
            $validated = $request->validate([
                'pkm_proposal_id' => 'required|exists:pkm_proposals,id',
                'description' => 'nullable|string|max:5000',
                'file_upload' => 'required|file|mimes:pdf|max:10240',
                'surat_tugas_upload' => 'required|file|mimes:pdf|max:10240',
            ], [
                'pkm_proposal_id.required' => 'Usulan PKM wajib dipilih untuk laporan akhir',
                'file_upload.required' => 'File laporan akhir wajib di-upload',
                'file_upload.mimes' => 'File harus berupa PDF',
                'file_upload.max' => 'Ukuran file maksimal 10MB',
                'surat_tugas_upload.required' => 'File surat tugas wajib di-upload',
                'surat_tugas_upload.mimes' => 'File surat tugas harus berupa PDF',
                'surat_tugas_upload.max' => 'File surat tugas maksimal berukuran 10MB',
            ]);
        } else {
            // LUARAN: File dan/atau Hyperlink
            $validated = $request->validate([
                'pkm_proposal_id' => 'nullable|exists:pkm_proposals,id',
                'jenis_luaran' => 'required|in:Artikel Jurnal Nasional Terakreditasi,Jurnal Internasional Bereputasi,Buku Referensi,Buku Ajar,Hak Cipta dan Paten,Lainnya, sebutkan',
                'jenis_luaran_lainnya' => 'required_if:jenis_luaran,Lainnya, sebutkan|nullable|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'file_upload' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'hyperlink' => 'nullable|url|max:500',
            ], [
                'jenis_luaran.required' => 'Jenis luaran wajib dipilih',
                'jenis_luaran_lainnya.required_if' => 'Mohon sebut jenis luaran lainnya',
                'title.required' => 'Judul luaran wajib diisi',
                'file_upload.mimes' => 'File harus berupa PDF, DOC, DOCX, JPG, atau PNG',
                'file_upload.max' => 'Ukuran file maksimal 10MB',
                'hyperlink.url' => 'Format URL tidak valid',
            ]);
            
            // Validasi custom: Minimal 1 harus diisi
            if (empty($request->file('file_upload')) && empty($validated['hyperlink'])) {
                return back()
                    ->withErrors(['file_upload' => 'Minimal salah satu harus diisi: File Upload atau Hyperlink URL'])
                    ->withInput();
            }
        }
        
        // Cek apakah PKM proposal milik user (hanya jika pkm_proposal_id ada)
        if (!empty($validated['pkm_proposal_id'])) {
            $pkmProposal = PkmProposal::findOrFail($validated['pkm_proposal_id']);
            
            if ($pkmProposal->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
            
            // Cek apakah PKM proposal sudah accepted
            if ($pkmProposal->status !== 'accepted') {
                return back()->with('error', 'Hanya usulan PKM yang sudah disetujui yang bisa upload laporan/luaran.');
            }
            
            // Cek apakah sudah ada laporan/luaran untuk PKM proposal ini
            $existingReport = Report::where('pkm_proposal_id', $validated['pkm_proposal_id'])
                ->where('type', $type)
                ->first();
            
            if ($existingReport) {
                return back()->with('error', 'Usulan PKM ini sudah memiliki ' . ($type === 'laporan_akhir' ? 'laporan akhir' : 'luaran') . '.');
            }
        }
        
        // Prepare data
        $data = [
            'pkm_proposal_id' => $validated['pkm_proposal_id'] ?? null,  // ← PKM ID
            'proposal_id' => null,                                        // ← NULL untuk PKM reports
            'user_id' => Auth::id(),
            'type' => $type,
            'title' => $type === 'laporan_akhir' && !empty($validated['pkm_proposal_id']) 
                ? PkmProposal::find($validated['pkm_proposal_id'])->judul 
                : ($validated['title'] ?? 'Tanpa Judul'),
            'description' => $validated['description'],
        ];
        
        // UPLOAD FILE LAPORAN AKHIR (2 file)
        if ($type === 'laporan_akhir') {
            // File 1: Laporan Akhir
            $file = $request->file('file_upload');
            $filePath = $file->store('pkm_reports/' . $type, 'public');  // ← PKM folder
            $fileSize = round($file->getSize() / 1024, 2); 
            $fileType = $file->getClientOriginalExtension();
            
            $data['file_path'] = $filePath;
            $data['file_size'] = $fileSize;
            $data['file_type'] = $fileType;

            // File 2: Surat Tugas
            $suratTugasFile = $request->file('surat_tugas_upload');
            $suratTugasPath = $suratTugasFile->store('pkm_reports/surat_tugas', 'public');  // ← PKM folder
            $suratTugasSize = round($suratTugasFile->getSize() / 1024, 2);
            $suratTugasType = $suratTugasFile->getClientOriginalExtension();

            $data['surat_tugas_path'] = $suratTugasPath;
            $data['surat_tugas_size'] = $suratTugasSize;
            $data['surat_tugas_type'] = $suratTugasType;
        }

        // HANDLE LUARAN: File dan/atau Hyperlink
        if ($type === 'luaran') {
            // Simpan jenis luaran
            $data['jenis_luaran'] = $validated['jenis_luaran'];
            
            if ($validated['jenis_luaran'] === 'Lainnya, sebutkan') {
                $data['jenis_luaran_lainnya'] = $validated['jenis_luaran_lainnya'] ?? null;
            }
            
            $hasFile = $request->hasFile('file_upload');
            $hasLink = !empty($validated['hyperlink']);
            
            // Upload file (jika ada)
            if ($hasFile) {
                $file = $request->file('file_upload');
                $filePath = $file->store('pkm_reports/luaran', 'public');  // ← PKM folder
                $fileSize = round($file->getSize() / 1024, 2);
                $fileType = $file->getClientOriginalExtension();
                
                $data['file_path'] = $filePath;
                $data['file_size'] = $fileSize;
                $data['file_type'] = $fileType;
            }
            
            // Simpan hyperlink (jika ada)
            if ($hasLink) {
                $data['hyperlink'] = $validated['hyperlink'];
            }
            
            // Tentukan luaran_type
            if ($hasFile && $hasLink) {
                $data['luaran_type'] = 'both';
            } elseif ($hasFile) {
                $data['luaran_type'] = 'file';
            } elseif ($hasLink) {
                $data['luaran_type'] = 'link';
            }
        }
        
        // Create report
        Report::create($data);
        
        $successMessage = $type === 'laporan_akhir' 
            ? 'Laporan akhir PKM berhasil di-upload!' 
            : 'Luaran PKM berhasil di-upload!';
        
        return redirect()
            ->route($type === 'laporan_akhir' ? 'pkm-reports.laporan-akhir' : 'pkm-reports.luaran')
            ->with('success', $successMessage);
    }

    /**
     * Show PKM report detail
     */
    public function show($type, Report $report)
    {
        // Authorization: Publisher lihat milik sendiri, Admin lihat semua
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        return view('pkm-reports.show', [
            'title' => 'Detail ' . ($type === 'laporan_akhir' ? 'Laporan Akhir PKM' : 'Luaran PKM'),
            'report' => $report->load('pkmProposal', 'author'),
            'type' => $type,
        ]);
    }

    /**
     * Delete PKM report
     */
    public function destroy($type, Report $report)
    {
        // Authorization
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Delete files if exist
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        if ($report->surat_tugas_path) {
            Storage::disk('public')->delete($report->surat_tugas_path);
        }   
        
        $report->delete();
        
        $successMessage = $type === 'laporan_akhir' 
            ? 'Laporan akhir PKM berhasil dihapus!' 
            : 'Luaran PKM berhasil dihapus!';
        
        // Redirect berdasarkan role
        if (Auth::user()->role === 'admin') {
            return redirect()
                ->route($type === 'laporan_akhir' ? 'admin.pkm-reports.laporan-akhir' : 'admin.pkm-reports.luaran')
                ->with('success', $successMessage);
        } else {
            return redirect()
                ->route($type === 'laporan_akhir' ? 'pkm-reports.laporan-akhir' : 'pkm-reports.luaran')
                ->with('success', $successMessage);
        }
    }

    /**
     * Download PKM report file
     */
    public function download($type, Report $report)
    {
        // Authorization
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        if (!$report->file_path) {
            abort(404, 'File tidak ditemukan (ini adalah luaran tipe hyperlink)');
        }
        
        $filePath = storage_path('app/public/' . $report->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di server');
        }
        
        return response()->download($filePath);
    }

    /**
     * Download surat tugas PKM
     */
    public function downloadSuratTugas($type, Report $report)
    {
        // Authorization
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        if (!$report->surat_tugas_path) {
            abort(404, 'File surat tugas tidak ditemukan');
        }
        
        $filePath = storage_path('app/public/' . $report->surat_tugas_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File surat tugas tidak ditemukan di server');
        }
        
        return response()->download($filePath);
    }

    /**
     * Admin: View all PKM reports
     */
    public function adminIndex(Request $request, $type = 'laporan_akhir')
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Validasi type
        if (!in_array($type, ['laporan_akhir', 'luaran'])) {
            abort(404);
        }
        
        // Query builder - ONLY PKM reports
        $query = Report::where('type', $type)
            ->whereNotNull('pkm_proposal_id')  // ← Filter PKM reports only
            ->with(['pkmProposal:id,judul', 'author:id,name,email'])
            ->latest();
        
        // Filter by publisher
        if ($request->filled('publisher')) {
            $query->where('user_id', $request->publisher);
        }
        
        // Filter by PKM proposal
        if ($request->filled('pkm_proposal')) {
            $query->where('pkm_proposal_id', $request->pkm_proposal);
        }
        
        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $reports = $query->paginate(15)->withQueryString();
        
        // Get all publishers for filter dropdown
        $publishers = \App\Models\User::where('role', 'publisher')
            ->orderBy('name')
            ->get();
        
        $title = 'Kelola ' . ($type === 'laporan_akhir' ? 'Laporan Akhir PKM' : 'Luaran PKM');
        
        return view('pkm-reports.admin-index', [
            'title' => $title,
            'reports' => $reports,
            'type' => $type,
            'publishers' => $publishers,
        ]);
    }
}