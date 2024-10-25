<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class BidController extends Controller
{
    public function allBids()
    {
        // Retrieve all bids except those placed by the current admin
        $bids = Bid::where('user_id', '!=', Auth::id())
                    ->with('project', 'user')
                    ->get();

        // Find the lowest bid for each project
        $lowestBids = Bid::select('project_id')
                         ->selectRaw('MIN(amount) as lowest_amount')
                         ->groupBy('project_id')
                         ->pluck('lowest_amount', 'project_id');

        return view('admin.bids.index', compact('bids', 'lowestBids'));
    }

    public function index()
    {
        // Get all bids placed by the authenticated user (freelancer) along with the associated projects
        $bids = Bid::where('user_id', Auth::id())->with('project')->paginate(10);

        // Pass the bids to a view to display them
        return view('bids.index', compact('bids'));
    }

    // Show the bidding form for a specific project by slug
    public function create(Project $project)
    {
        return view('bids.create', compact('project'));
    }

    // Store the bid in the database for a specific project by slug
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:999999999.99',
            'remarks' => 'nullable|string',
        ]);

        // Create a new bid for the authenticated user
        Bid::create([
            'user_id' => auth()->id(),        // Ensure the logged-in user ID is stored
            'project_id' => $project->id,     // Get the project ID
            'amount' => $request->amount,     // Bid amount
            'remarks' => $request->remarks,   // Any remarks
        ]);

        return redirect()->route('projects.show', $project->slug)
                        ->with('success', 'Your bid has been submitted successfully!');
    }


    public function history(Project $project)
    {
        // Ensure only the authenticated user's bids are shown for the specific project
        $bids = Bid::where('project_id', $project->id)
                   ->where('user_id', Auth::id()) // Fetch only the current user's bids
                   ->with('project') // Load the related project to check its status
                   ->get();

        // Pass the bids to the view
        return view('bids.history', compact('bids', 'project'));
    }
    public function accept(Bid $bid)
    {
        // Check if the user is the bid winner and the bid has not already been accepted or rejected
        if (auth()->id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403); // Unauthorized action
        }

        // Mark the bid as accepted and update the timestamp
        $bid->update([
            'is_accepted' => true,
            'accepted_at' => now(),
        ]);

           return redirect()->back()->with('success', 'You have accepted the bid.');

    }

    public function reject(Bid $bid)
    {
        // Check if the user is the bid winner and the bid has not already been accepted or rejected
        if (auth()->id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403); // Unauthorized action
        }

        // Mark the bid as rejected
        $bid->update([
            'is_accepted' => false,
        ]);

            return redirect()->back()->with('success', 'You have rejected the bid.');

    }

}
