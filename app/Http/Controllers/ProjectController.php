<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Bid;
use App\Notifications\BidAccepted;
use App\Notifications\BidWonNotification;


class ProjectController extends Controller
{
    public function index()
    {
        // Retrieve all projects
        $projects = Project::all()->paginate(10);

        // Return the view and pass the projects
        return view('projects.index', compact('projects'));
    }

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
        // Reset all other bids for this project to is_winner = false
        Bid::where('project_id', $project->id)->update(['is_winner' => false]);

        // Mark the selected bid as the winner
        $bid->is_winner = true;
        $bid->save();

        // Optionally, notify the freelancer about the winning bid
        $bid->user->notify(new BidWonNotification($bid));

        return redirect()->route('admin.projects.bids', $project->id)->with('success', 'The winner has been set.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function close(Project $project)
    {
        $project->update(['status' => 'Closed']);

        return redirect()->route('projects.index')->with('success', 'Project closed successfully.');
    }

    public function open(Project $project)
    {
        $project->update(['status' => 'Open']);

        return redirect()->route('projects.index')->with('success', 'Project opened successfully.');
    }
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

        return view('projects.show', compact('project', 'userBid', 'winningBid'));
    }

}
