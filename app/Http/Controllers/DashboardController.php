<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        // Admin-specific data
        if (Auth::user()->hasRole('admin')) {
            $userCount = User::count();
            $projectCount = Project::count();
            $bidCount = Bid::count();

            return view('dashboard', [
                'userCount' => $userCount,
                'projectCount' => $projectCount,
                'bidCount' => $bidCount,
                'role' => 'admin',
            ]);
        }

        // Producer-specific data
        if (Auth::user()->hasRole('producer')) {
             $userCount = User::count();
            $projectCount = Project::count();
            $bidCount = Bid::count();

            return view('dashboard', [
                'userCount' => $userCount,
                'projectCount' => $projectCount,
                'bidCount' => $bidCount,
                'role' => 'admin',
            ]);
        }

        // Freelancer-specific data
        if (Auth::user()->hasRole('freelancer')) {
            $bidCount = Bid::where('user_id', Auth::id())->count();
            $projectsWon = Bid::where('user_id', Auth::id())->where('is_winner', true)->count();

            return view('dashboard', [
                'bidCount' => $bidCount,
                'projectsWon' => $projectsWon,
                'role' => 'freelancer',
            ]);
        }

        // Default return (for other roles or guests)
        return view('dashboard');
    }
}
