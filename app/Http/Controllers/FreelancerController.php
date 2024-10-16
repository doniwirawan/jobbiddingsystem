<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

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
        // Show project details for freelancers
        return view('freelancer.projects.show', compact('project'));
    }
}
