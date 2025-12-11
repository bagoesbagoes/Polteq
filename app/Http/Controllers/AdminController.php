<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $proposals = \App\Models\Proposal::latest()->get();
        return view('admin.dashboard', compact('proposals'));
    }

    public function createReviewer()
    {
        return view('admin.create-reviewer');
    }

    public function storeReviewer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'reviewer',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Reviewer dibuat.');
    }
}
