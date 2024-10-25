<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Notifications\BidAccepted;
use App\Notifications\BidWonNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

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
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }

        return view('projects.create');
    }


    // Store the newly created project in the database
    public function store(Request $request)
    {
         if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
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

        // Generate a slug from the project name
        $slug = Str::slug($request->name);

        // Ensure the slug is unique by checking if any other projects have the same slug
        $existingSlugs = Project::where('slug', 'like', $slug . '%')->pluck('slug');

        if ($existingSlugs->contains($slug)) {
            $slug = $slug . '-' . ($existingSlugs->count() + 1); // Append number if a conflict exists
        }

        Project::create([
            'name' => $request->name,
            'slug' => $slug,
            'date' => $request->date,
            'entity' => $request->entity,
            'type' => $request->type,
            'rate' => $request->rate,
            'role' => $request->role,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'created_by' => auth()->user()->id,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    // Display bids for a specific project, showing the lowest bid
    public function showBids(Project $project)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        // Retrieve all bids for this project, ordered by amount (lowest first)
        $bids = Bid::where('project_id', $project->id)->orderBy('amount', 'asc')->get();

        // The lowest bid (if available)
        $lowestBid = $bids->first();

        return view('admin.projects.bids', compact('project', 'bids', 'lowestBid'));
    }

    // public function markWinner(Project $project, Bid $bid)
    // {
    //     // Authorize only admin or producer to mark winner
    //     if (!auth()->user()->hasRole(['admin', 'producer'])) {
    //         abort(403); // Unauthorized access
    //     }

    //     // Reset all other bids for this project to is_winner = false
    //     // Bid::where('project_id', $project->id)->update(['is_winner' => false]);
    //      Bid::where('project_id', $project->slug)->update(['is_winner' => false]);


    //     // Mark the selected bid as the winner
    //     $bid->is_winner = true;
    //     $bid->deadline = now()->addHours(24);  // Example: setting a deadline 24 hours later

    //     // Ensure the user_id is properly set when updating
    //     $bid->user_id = $bid->user_id ?? auth()->id(); // Or ensure the user ID is already present
        
    //     $bid->save();

    //     // Optionally, notify the freelancer about the winning bid
    //     $bid->user->notify(new BidWonNotification($bid));

    //     return redirect()->route('admin.projects.bids', $project->slug)
    //                     ->with('success', 'Bid has been marked as the winner.');
    // }
    // public function markWinner(Request $request, Project $project, Bid $bid)
    // {
    //     // Authorize only admin or producer to mark a winner
    //     if (!auth()->user()->hasRole(['admin', 'producer'])) {
    //         return response()->json(['error' => 'Unauthorized access.'], 403);
    //     }

    //     // Reset all other bids for this project to is_winner = false
    //     Bid::where('project_id', $project->id)->update(['is_winner' => false]);

    //     // Mark the selected bid as the winner
    //     $bid->is_winner = true;
    //     $bid->deadline = now()->addHours(24);  // Set a deadline 24 hours later for acceptance

    //     // Ensure the user_id is properly set when updating
    //     $bid->user_id = $bid->user_id ?? auth()->id(); // Ensure the user ID is present

    //     $bid->save();

    //     // Notify the freelancer about the winning bid
    //     $bid->user->notify(new BidWonNotification($bid));

    //     // Return success JSON response
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Bid has been marked as the winner.',
    //         'bid_id' => $bid->id,
    //     ]);
    // }
    // public function markWinner(Project $project, Bid $bid)
    // {
    //     if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
    //         abort(403);  // Unauthorized access, redirect to custom 403 page
    //     }
    //     // Reset all other bids for this project to is_winner = false
    //     Bid::where('project_slug', $project->slug)->update(['is_winner' => false]);

    //     // Mark the selected bid as the winner
    //     $bid->is_winner = true;
    //     $bid->save();

    //     // Optionally, notify the freelancer about the winning bid
    //     $bid->user->notify(new BidWonNotification($bid));

    //     return redirect()->route('admin.projects.bids', $project->id)->with('success', 'The winner has been set.');
    // }
    public function markWinner(Project $project, Bid $bid)
    {
        // Authorize only admin or producer to mark winner
        if (!auth()->user()->hasRole(['admin', 'producer'])) {
            abort(403); // Unauthorized access
        }

        // Reset all other bids for this project to is_winner = false
        Bid::where('project_id', $project->id)->update(['is_winner' => false]);

        // Mark the selected bid as the winner
        $bid->is_winner = true;
        $bid->deadline = now()->addHours(24);  // Set a 24-hour deadline for acceptance
        $bid->save();

        // Notify the freelancer about the winning bid
        $bid->user->notify(new BidWonNotification($bid));

        return redirect()->route('admin.projects.bids', $project->slug)
                        ->with('success', 'Bid has been marked as the winner.');
    }



    public function destroy(Project $project)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function close(Project $project)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $project->update(['status' => 'Closed']);

        return redirect()->route('projects.index')->with('success', 'Project closed successfully.');
    }

    public function open(Project $project)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'producer') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $project->update(['status' => 'Open']);

        return redirect()->route('projects.index')->with('success', 'Project opened successfully.');
    }
    public function show(Project $project)
    {
        // This already uses implicit route model binding, and will now use slugs
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

    public function edit(Project $project)
    {
        // Return the edit view with the project data
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
         $request->validate([
        'name' => 'required|string|max:255',
        'date' => 'required|date',
        'entity' => 'required|string',
        'type' => 'required|string',
        'rate' => 'required|numeric',
        'role' => 'required|string',
        'remarks' => 'nullable|string',
        'status' => 'required|string',
    ]);

    // Only generate a new slug if the name has changed
    if ($request->name !== $project->name) {
        $slug = Str::slug($request->name);

        // Ensure the slug is unique
        $existingSlugs = Project::where('slug', 'like', $slug . '%')->pluck('slug');

        if ($existingSlugs->contains($slug)) {
            $slug = $slug . '-' . ($existingSlugs->count() + 1); // Append number if conflict
        }

        $project->slug = $slug;
    }

    $project->update([
        'name' => $request->name,
        'date' => $request->date,
        'entity' => $request->entity,
        'type' => $request->type,
        'rate' => $request->rate,
        'role' => $request->role,
        'remarks' => $request->remarks,
        'status' => $request->status,
    ]);

    return redirect()->route('projects.show', $project->slug)->with('success', 'Project updated successfully.');
    }
    public function markWinnerAjax(Request $request, Project $project, Bid $bid)
    {
        // Authorize only admin or producer to mark a winner
        if (!auth()->user()->hasRole(['admin', 'producer'])) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        // Reset all other bids for this project to is_winner = false
        Bid::where('project_id', $project->id)->update(['is_winner' => false]);

        // Mark the selected bid as the winner
        $bid->is_winner = true;
        $bid->deadline = now()->addHours(24);  // Set a deadline 24 hours later for acceptance

        // Ensure the user_id is properly set when updating
        $bid->user_id = $bid->user_id ?? auth()->id(); // Ensure the user ID is present

        $bid->save();

        // Notify the freelancer about the winning bid
        $bid->user->notify(new BidWonNotification($bid));

        // Return success JSON response
        return response()->json([
            'success' => true,
            'message' => 'Bid has been marked as the winner.',
            'bid_id' => $bid->id,
        ]);
    }


}
