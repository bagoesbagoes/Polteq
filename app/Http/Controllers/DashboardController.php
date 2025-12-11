<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ManajemenProposalPenelitian',[
            'title' => 'Manajemen Proposal usulan',
            'active' => 'dashboard',
        ]);
    }
}
