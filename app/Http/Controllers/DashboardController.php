<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Admin-specific data
        if (Auth::user()->hasRole('admin')) {
            $userCount = User::count();
            $projectCount = Project::count();
            $bidCount = Bid::count();

            return view('dashboard', compact('userCount', 'projectCount', 'bidCount'));
        }

        // Producer-specific data
        if (Auth::user()->hasRole('producer')) {
            $projectCount = Project::where('created_by', Auth::id())->count();
            $totalBidsReceived = Bid::whereIn('project_id', Project::where('created_by', Auth::id())->pluck('id'))->count();

            return view('dashboard', compact('projectCount', 'totalBidsReceived'));
        }

        // Freelancer-specific data
        if (Auth::user()->hasRole('freelancer')) {
            $bidCount = Bid::where('user_id', Auth::id())->count();
            $projectsWon = Bid::where('user_id', Auth::id())->where('is_winner', true)->count();

            return view('dashboard', compact('bidCount', 'projectsWon'));
        }

        // Default return (for other roles or guests, if applicable)
        return view('dashboard');
    }
}
