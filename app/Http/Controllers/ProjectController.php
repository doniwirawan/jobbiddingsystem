<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Bid;
use App\Notifications\BidAccepted;

class ProjectController extends Controller
{
    // Show the form for creating a new project
    public function create()
    {
        return view('projects.create');
    }

    // Store the newly created project in the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'entity' => 'required|string|in:Corp,Weddings,Studio',
            'type' => 'required|string|in:Photography,Videography',
            'rate' => 'required|numeric',
            'role' => 'required|string|in:Primary,Secondary',
            'remarks' => 'nullable|string',
            'status' => 'required|string|in:Open,Closed',
        ]);

        Project::create([
            'name' => $request->name,
            'date' => $request->date,
            'entity' => $request->entity,
            'type' => $request->type,
            'rate' => $request->rate,
            'role' => $request->role,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);

        return redirect()->route('projects.create')->with('success', 'Project created successfully.');
    }

    // Display bids for a specific project, showing the lowest bid
    public function showBids(Project $project)
    {
        // Retrieve all bids for this project, ordered by amount (lowest first)
        $bids = Bid::where('project_id', $project->id)->orderBy('amount', 'asc')->get();

        // The lowest bid (if available)
        $lowestBid = $bids->first();

        return view('admin.projects.bids', compact('project', 'bids', 'lowestBid'));
    }

    public function markWinner(Project $project, Bid $bid)
    {
        // Reset other bids for this project
        Bid::where('project_id', $project->id)->update(['is_winner' => false]);

        // Mark the selected bid as the winner
        $bid->update(['is_winner' => true]);

        return redirect()->back()->with('success', 'The bid has been marked as the winner.');
    }

}
