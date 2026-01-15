<?php

namespace App\Http\Controllers;

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
        
        // JIKA REVIEWER: Hitung SEMUA usulan berdasarkan status (global)
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
        
        return view('ManajemenProposalPenelitian', [
            'title' => 'Manajemen usulan',
            'active' => 'dashboard',
            'counts' => $counts,
        ]);
    }
}