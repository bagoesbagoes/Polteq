<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Proposal;
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

}