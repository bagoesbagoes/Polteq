<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display list of reports by type (for Publisher)
     */
    public function index($type = 'laporan_akhir')
    {
        // Validasi type
        if (!in_array($type, ['laporan_akhir', 'luaran'])) {
            abort(404);
        }
        
        // Publisher hanya lihat laporan sendiri
        $reports = Report::where('user_id', Auth::id())
            ->where('type', $type)
            ->with('proposal:id,judul')
            ->latest()
            ->paginate(10);
        
        $title = $type === 'laporan_akhir' ? 'Daftar Laporan Akhir' : 'Daftar Luaran';
        
        return view('reports.index', [
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
        
        // Get accepted proposals yang belum punya laporan/luaran
        $proposals = Proposal::where('user_id', Auth::id())
            ->where('status', 'accepted')
            ->whereDoesntHave('reports', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->get();
        
        // HANYA untuk laporan_akhir, wajib ada proposal
        if ($type === 'laporan_akhir' && $proposals->isEmpty()) {
            return redirect()
                ->route('laporan_penelitian')
                ->with('error', 'Tidak ada usulan yang disetujui atau semua usulan sudah memiliki laporan akhir.');
        }
        
        // Untuk luaran, boleh kosong (bisa upload tanpa proposal)
        
        $title = $type === 'laporan_akhir' ? 'Upload Laporan Akhir' : 'Upload Luaran';
        
        return view('reports.create', [
            'title' => $title,
            'type' => $type,
            'proposals' => $proposals,
        ]);
    }

    /**
     * Store new report
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
                'proposal_id' => 'required|exists:proposals,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'file_upload' => 'required|file|mimes:pdf|max:10240',
            ], [
                'proposal_id.required' => 'Usulan wajib dipilih untuk laporan akhir',
                'file_upload.required' => 'File laporan akhir wajib di-upload',
                'file_upload.mimes' => 'File harus berupa PDF',
                'file_upload.max' => 'Ukuran file maksimal 10MB',
            ]);
        } else {
            // Luaran: proposal_id OPSIONAL
            $validated = $request->validate([
                'proposal_id' => 'nullable|exists:proposals,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'luaran_type' => 'required|in:file,link',
                'file_upload' => 'nullable|required_if:luaran_type,file|file|max:10240',
                'hyperlink' => 'nullable|required_if:luaran_type,link|url|max:500',
            ], [
                'luaran_type.required' => 'Pilih tipe luaran (File atau Hyperlink)',
                'file_upload.required_if' => 'File wajib di-upload jika memilih tipe File',
                'file_upload.max' => 'Ukuran file maksimal 10MB',
                'hyperlink.required_if' => 'URL wajib diisi jika memilih tipe Hyperlink',
                'hyperlink.url' => 'Format URL tidak valid',
            ]);
        }
        
        // Cek apakah proposal milik user (hanya jika proposal_id ada)
        if (!empty($validated['proposal_id'])) {
            $proposal = Proposal::findOrFail($validated['proposal_id']);
            
            if ($proposal->user_id !== Auth::id()) {
                abort(403, 'Unauthorized');
            }
            
            // Cek apakah proposal sudah accepted
            if ($proposal->status !== 'accepted') {
                return back()->with('error', 'Hanya usulan yang sudah disetujui yang bisa upload laporan/luaran.');
            }
            
            // Cek apakah sudah ada laporan/luaran untuk proposal ini
            $existingReport = Report::where('proposal_id', $validated['proposal_id'])
                ->where('type', $type)
                ->first();
            
            if ($existingReport) {
                return back()->with('error', 'Proposal ini sudah memiliki ' . ($type === 'laporan_akhir' ? 'laporan akhir' : 'luaran') . '.');
            }
        }
        
        // Prepare data
        $data = [
            'proposal_id' => $validated['proposal_id'] ?? null,
            'user_id' => Auth::id(),
            'type' => $type,
            'title' => $validated['title'],
            'description' => $validated['description'],
        ];
        
        // Handle file upload (untuk laporan_akhir atau luaran tipe file)
        if ($type === 'laporan_akhir' || ($type === 'luaran' && $validated['luaran_type'] === 'file')) {
            $file = $request->file('file_upload');
            $filePath = $file->store('reports/' . $type, 'public');
            $fileSize = round($file->getSize() / 1024, 2); // KB
            $fileType = $file->getClientOriginalExtension();
            
            $data['file_path'] = $filePath;
            $data['file_size'] = $fileSize;
            $data['file_type'] = $fileType;
        }
        
        // Handle hyperlink (untuk luaran tipe link)
        if ($type === 'luaran') {
            $data['luaran_type'] = $validated['luaran_type'];
            
            if ($validated['luaran_type'] === 'link') {
                $data['hyperlink'] = $validated['hyperlink'];
            }
        }
        
        // Create report
        Report::create($data);
        
        $successMessage = $type === 'laporan_akhir' 
            ? 'Laporan akhir berhasil di-upload!' 
            : 'Luaran berhasil di-upload!';
        
        return redirect()
            ->route('laporan_penelitian')
            ->with('success', $successMessage);
}


    /**
     * Show report detail
     */
    public function show($type, Report $report)
    {
        // Authorization: Publisher lihat milik sendiri, Admin lihat semua
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        return view('reports.show', [
            'title' => 'Detail ' . ($type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran'),
            'report' => $report->load('proposal', 'author'),
            'type' => $type,
        ]);
    }

    /**
     * Delete report (Publisher atau Admin)
     */
    public function destroy($type, Report $report)
    {
        // Authorization: Publisher hapus milik sendiri, Admin hapus semua
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Delete file if exists
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }
        
        $report->delete();
        
        $successMessage = $type === 'laporan_akhir' 
            ? 'Laporan akhir berhasil dihapus!' 
            : 'Luaran berhasil dihapus!';
        
        // Redirect berdasarkan role
        if (Auth::user()->role === 'admin') {
            // Admin redirect ke halaman admin
            return redirect()
                ->route($type === 'laporan_akhir' ? 'admin.reports.laporan-akhir' : 'admin.reports.luaran')
                ->with('success', $successMessage);
        } else {
            // Publisher redirect ke halaman publisher
            return redirect()
                ->route($type === 'laporan_akhir' ? 'reports.laporan-akhir' : 'reports.luaran')
                ->with('success', $successMessage);
        }
    }

    /**
     * Download report file
     */
    public function download($type, Report $report)
    {
        // Authorization
        if ($report->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        // Check if file exists
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
     * Admin: View all reports
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
        
        // Query builder
        $query = Report::where('type', $type)
            ->with(['proposal:id,judul', 'author:id,name,email'])
            ->latest();
        
        // Filter by publisher (if specified)
        if ($request->filled('publisher')) {
            $query->where('user_id', $request->publisher);
        }
        
        // Filter by proposal (if specified)
        if ($request->filled('proposal')) {
            $query->where('proposal_id', $request->proposal);
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
        
        $title = 'Kelola ' . ($type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran');
        
        return view('reports.admin-index', [
            'title' => $title,
            'reports' => $reports,
            'type' => $type,
            'publishers' => $publishers,
        ]);
    }
}