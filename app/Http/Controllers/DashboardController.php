<?php

namespace App\Http\Controllers;

use App\Models\PkmProposal;
use App\Models\Proposal;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Inisialisasi counts berdasarkan role
        $counts = [
            'submitted' => 0,
            'accepted' => 0,
            'need_revision' => 0,
            'all' => 0,
        ];
        
        // JIKA PUBLISHER: Hitung usulan milik user saja
        if ($user->role === 'publisher') {
            $counts = [
                // Usulan yang SEDANG DIPROSES (status: submitted)
                'all' => Proposal::where('user_id', $user->id)
                                ->where('status', 'submitted')
                                ->count(),
                                      
                // Usulan yang sudah DITERIMA
                'accepted' => Proposal::where('user_id', $user->id)
                                     ->where('status', 'accepted')
                                     ->count(),
                                     
                // Usulan yang perlu REVISI
                'need_revision' => Proposal::where('user_id', $user->id)
                                           ->where('status', 'need_revision')
                                           ->count(),
            ];
        }
        
        // JIKA REVIEWER: Hitung SEMUA usulan berdasarkan status
        elseif ($user->role === 'reviewer') {
            $counts = [
                // Total SEMUA proposal dengan status submitted
                'all' => Proposal::where('status', 'submitted')->count(),
                
                // Total SEMUA proposal yang accepted
                'accepted' => Proposal::where('status', 'accepted')->count(),
                
                // Total SEMUA proposal yang need_revision
                'need_revision' => Proposal::where('status', 'need_revision')->count(),
            ];
        }
        
        // JIKA ADMIN: Hitung semua usulan
        elseif ($user->role === 'admin') {
            $counts = [
                'all' => Proposal::where('status', 'submitted')->count(),
                'accepted' => Proposal::where('status', 'accepted')->count(),
                'need_revision' => Proposal::where('status', 'need_revision')->count(),
            ];
        }
        
        return view('UsulanPenelitian', [
            'title' => 'Pengusulan',
            'active' => 'dashboard',
            'counts' => $counts,
        ]);
    }

    public function IndexLaporanPenelitian()
    {
        $user = Auth::user();
        
        // Initialize counts
        $counts = [
            'laporan_akhir' => 0,
            'luaran' => 0,
            'accepted_proposals' => 0,
        ];
        
        // Jika Publisher
        if ($user->role === 'publisher') {
            // Hitung jumlah proposals yang accepted
            $counts['accepted_proposals'] = Proposal::where('user_id', $user->id)
                ->where('status', 'accepted')
                ->count();
            
            // Hitung jumlah laporan akhir yang sudah diupload
            $counts['laporan_akhir'] = Report::where('user_id', $user->id)
                ->where('type', 'laporan_akhir')
                ->count();
            
            // Hitung jumlah luaran yang sudah diupload
            $counts['luaran'] = Report::where('user_id', $user->id)
                ->where('type', 'luaran')
                ->count();
        }
        
        // Jika Admin
        elseif ($user->role === 'admin') {
            $counts['accepted_proposals'] = Proposal::where('status', 'accepted')->count();
            $counts['laporan_akhir'] = Report::where('type', 'laporan_akhir')->count();
            $counts['luaran'] = Report::where('type', 'luaran')->count();
        }
        
        return view('LaporanPenelitian', [
            'title' => 'Laporan Penelitian',
            'active' => 'laporan_penelitian',
            'counts' => $counts,
        ]);
    }

    public function indexPkm(Request $request)
    {
        $user = Auth::user();
        
        // Initialize data
        $stats = [];
        $pkms = null;
        $publishers = null;
        
        // PUBLISHER
        if ($user->role === 'publisher') {
            // Stats
            $stats = [
                'terkirim' => PkmProposal::where('user_id', $user->id)
                    ->whereIn('status', ['draft', 'submitted'])
                    ->count(),
                'disetujui' => PkmProposal::where('user_id', $user->id)
                    ->where('status', 'accepted')
                    ->count(),
                'revisi' => PkmProposal::where('user_id', $user->id)
                    ->where('status', 'need_revision')
                    ->count(),
            ];

            // Recent PKM (latest 6)
            $pkms = PkmProposal::where('user_id', $user->id)
                ->latest()
                ->take(6)
                ->get();

            return view('pkm.dashboard-publisher', [
                'title' => 'Dashboard Usulan PKM',
                'stats' => $stats,
                'pkms' => $pkms,
            ]);
        }
        
        // REVIEWER
        elseif ($user->role === 'reviewer') {
            // Build query
            $query = PkmProposal::with('author')
                ->whereNotIn('status', ['draft']);

            // SEARCH
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('judul', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
                });
            }

            // FILTER: Status
            if ($request->filled('status')) {
                $validStatuses = ['submitted', 'accepted', 'need_revision'];
                if (in_array($request->status, $validStatuses)) {
                    $query->where('status', $request->status);
                }
            }

            // SORT
            $sortBy = $request->get('sort', 'latest');
            if ($sortBy === 'oldest') {
                $query->oldest('created_at');
            } else {
                $query->latest('created_at');
            }

            $pkms = $query->paginate(12)->withQueryString();

            // Stats
            $stats = [
                'submitted' => PkmProposal::where('status', 'submitted')->count(),
                'accepted' => PkmProposal::where('status', 'accepted')->count(),
                'need_revision' => PkmProposal::where('status', 'need_revision')->count(),
            ];

            return view('pkm.dashboard-reviewer', [
                'title' => 'Dashboard Review PKM',
                'pkms' => $pkms,
                'stats' => $stats,
            ]);
        }
        
        // ADMIN
        elseif ($user->role === 'admin') {
            // Build query
            $query = PkmProposal::with('author')
                ->whereNotIn('status', ['draft']);

            // SEARCH
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('judul', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
                });
            }

            // FILTER: Status
            if ($request->filled('status')) {
                $validStatuses = ['submitted', 'accepted', 'need_revision'];
                if (in_array($request->status, $validStatuses)) {
                    $query->where('status', $request->status);
                }
            }

            // FILTER: Author
            if ($request->filled('author')) {
                $query->where('user_id', $request->author);
            }

            // SORT
            $sortBy = $request->get('sort', 'latest');
            if ($sortBy === 'oldest') {
                $query->oldest('created_at');
            } else {
                $query->latest('created_at');
            }

            $pkms = $query->paginate(12)->withQueryString();

            // Stats
            $stats = [
                'total' => PkmProposal::whereNotIn('status', ['draft'])->count(),
                'submitted' => PkmProposal::where('status', 'submitted')->count(),
                'accepted' => PkmProposal::where('status', 'accepted')->count(),
                'revisi' => PkmProposal::where('status', 'need_revision')->count(),
            ];

            // Get publishers for filter
            $publishers = User::where('role', 'publisher')
                ->orderBy('name')
                ->get();

            return view('pkm.dashboard-admin', [
                'title' => 'Kelola Usulan PKM',
                'pkms' => $pkms,
                'stats' => $stats,
                'publishers' => $publishers,
            ]);
        }
        
        // Jika role tidak dikenali
        abort(403, 'Unauthorized');
    }

}