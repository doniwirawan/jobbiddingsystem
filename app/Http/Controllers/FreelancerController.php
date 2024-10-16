<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\Bid;



class FreelancerController extends Controller
{
    // Method to display the list of projects
    public function index(Request $request)
    {
        // Fetch projects that are open and apply filters based on the request
        $projects = Project::where('status', 'Open')
            ->when($request->entity, function ($query) use ($request) {
                return $query->where('entity', $request->entity);
            })
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->when($request->role, function ($query) use ($request) {
                return $query->where('role', $request->role);
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        // Return the view with the projects
        return view('freelancer.projects.index', compact('projects'));
    }

    // Method to show the details of a specific project
    public function show(Project $project)
    {
        $userBid = null;
        $winningBid = null;

        if (Auth::check() && Auth::user()->hasRole('freelancer')) {
            // Get the authenticated user's bid for this project (if any)
            $userBid = Bid::where('user_id', Auth::id())->where('project_id', $project->id)->first();

            // Check if the user has the winning bid
            $winningBid = Bid::where('user_id', Auth::id())
                            ->where('project_id', $project->id)
                            ->where('is_winner', true)
                            ->first();
        }

        return view('freelancer.projects.show', compact('project', 'userBid', 'winningBid'));
    }

}
