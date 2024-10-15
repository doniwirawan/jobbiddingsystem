<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class BidController extends Controller
{
    public function index()
    {
        // Get all bids placed by the authenticated user (freelancer) along with the associated projects
        $bids = Bid::where('user_id', Auth::id())->with('project')->get();

        // Pass the bids to a view to display them
        return view('bids.index', compact('bids'));
    }

    // Show the bidding form for a specific project
    public function create(Project $project)
    {
        return view('bids.create', compact('project'));
    }

    // Store the bid in the database
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'remarks' => 'nullable|string',
        ]);

        // Create a new bid for the authenticated freelancer
        Bid::create([
            'user_id' => Auth::id(),           // Get the logged-in user's ID
            'project_id' => $project->id,      // Get the project ID
            'amount' => $request->amount,      // Get the bid amount from the form
            'remarks' => $request->remarks,    // Get any remarks from the form
        ]);

        return redirect()->route('projects.show', $project->id)
                        ->with('success', 'Your bid has been submitted!');
    }

}
