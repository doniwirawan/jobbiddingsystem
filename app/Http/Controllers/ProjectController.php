<?php

namespace App\Http\Controllers;

use App\Models\{Project, Bid};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\{BidAccepted, BidWonNotification};

class ProjectController extends Controller
{
    public function index()
    {
        // Retrieve and paginate all projects
        $projects = Project::paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        return view('projects.create');
    }

    public function store(Request $request)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'entity' => 'required|string|in:Corp,Weddings,Studio',
            'type' => 'required|string|in:Photography,Videography',
            'rate' => 'required|numeric',
            'role' => 'required|string|in:Primary,Secondary',
            'remarks' => 'nullable|string',
            'status' => 'required|string|in:Open,Closed',
        ]);

        $slug = $this->generateUniqueSlug($validated['name']);
        
        Project::create(array_merge($validated, [
            'slug' => $slug,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        // Check if the user has the required role to edit the project
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        // Return the edit view with the project data
        return view('projects.edit', compact('project'));
    }

    public function show(Project $project)
    {
        $userBid = $winningBid = null;

        if (Auth::check() && Auth::user()->hasRole('freelancer')) {
            $userBid = $project->bids()->where('user_id', Auth::id())->first();
            $winningBid = $project->bids()->where('user_id', Auth::id())->where('is_winner', true)->first();
        }

        return view('projects.show', compact('project', 'userBid', 'winningBid'));
    }

    public function showBids(Project $project)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $bids = $project->bids()->orderBy('amount', 'asc')->get();
        $lowestBid = $bids->first();

        return view('admin.projects.bids', compact('project', 'bids', 'lowestBid'));
    }

    public function markWinner(Project $project, Bid $bid)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $project->bids()->update(['is_winner' => false]);
        
        $bid->update([
            'is_winner' => true,
            'deadline' => now()->addHours(24),
        ]);

        $bid->user->notify(new BidWonNotification($bid));

        return redirect()->route('admin.projects.bids', $project->slug)
                         ->with('success', 'Bid has been marked as the winner.');
    }

    public function update(Request $request, Project $project)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date|before_or_equal:end_date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'entity' => 'required|string|in:Corp,Weddings,Studio',
            'type' => 'required|string|in:Photography,Videography',
            'rate' => 'required|numeric',
            'role' => 'required|string|in:Primary,Secondary',
            'remarks' => 'nullable|string',
            'status' => 'required|string|in:Open,Closed',
        ]);

        if ($request->name !== $project->name) {
            $project->slug = $this->generateUniqueSlug($validated['name']);
        }

        $project->update($validated);

        return redirect()->route('projects.show', $project->slug)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    public function close(Project $project)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $project->update(['status' => 'Closed']);

        return redirect()->route('projects.index')->with('success', 'Project closed successfully.');
    }

    public function open(Project $project)
    {
        if (!$this->isAdminOrProducer()) {
            abort(403);
        }

        $project->update(['status' => 'Open']);

        return redirect()->route('projects.index')->with('success', 'Project opened successfully.');
    }

    public function markWinnerAjax(Request $request, Project $project, Bid $bid)
    {
        if (!$this->isAdminOrProducer()) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $project->bids()->update(['is_winner' => false]);

        $bid->update([
            'is_winner' => true,
            'deadline' => now()->addHours(24),
        ]);

        $bid->user->notify(new BidWonNotification($bid));

        return response()->json([
            'success' => true,
            'message' => 'Bid has been marked as the winner.',
            'bid_id' => $bid->id,
        ]);
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = Project::where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    private function isAdminOrProducer()
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'producer']);
    }
}
