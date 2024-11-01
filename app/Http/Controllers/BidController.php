<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function allBids()
    {
        $bids = Bid::where('user_id', '!=', Auth::id())
                    ->with('project', 'user')
                    ->get();

        $lowestBids = Bid::select('project_id')
                         ->selectRaw('MIN(amount) as lowest_amount')
                         ->groupBy('project_id')
                         ->pluck('lowest_amount', 'project_id');

        return view('admin.bids.index', compact('bids', 'lowestBids'));
    }

    public function index()
    {
        $bids = Bid::where('user_id', Auth::id())->with('project')->paginate(10);
        return view('bids.index', compact('bids'));
    }

    public function create(Project $project)
    {
        // Check if a bid already exists for the user and this project
        $existingBid = Bid::where('user_id', Auth::id())
                          ->where('project_id', $project->id)
                          ->first();

        return view('bids.create', compact('project', 'existingBid'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0|max:999999999.99',
            'remarks' => 'nullable|string',
        ]);

        // Check if the user already has a bid on this project
        $existingBid = Bid::where('user_id', auth()->id())
                          ->where('project_id', $project->id)
                          ->first();

        if ($existingBid) {
            // Update existing bid
            $existingBid->update([
                'amount' => $request->amount,
                'remarks' => $request->remarks,
                'is_winner' => false, // Reset winner status
            ]);

            $message = 'Your bid has been updated successfully!';
        } else {
            // Create a new bid
            Bid::create([
                'user_id' => auth()->id(),
                'project_id' => $project->id,
                'amount' => $request->amount,
                'remarks' => $request->remarks,
            ]);

            $message = 'Your bid has been submitted successfully!';
        }

        return redirect()->route('projects.show', $project->slug)
                        ->with('success', $message);
    }

    public function history(Project $project)
    {
        $bids = Bid::where('project_id', $project->id)
                   ->where('user_id', Auth::id())
                   ->with('project')
                   ->get();

        return view('bids.history', compact('bids', 'project'));
    }

    public function accept(Bid $bid)
    {
        if (auth()->id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403);
        }

        $bid->update([
            'is_accepted' => true,
            'accepted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'You have accepted the bid.');
    }

    public function reject(Bid $bid)
    {
        if (auth()->id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403);
        }

        $bid->update(['is_accepted' => false]);

        return redirect()->back()->with('success', 'You have rejected the bid.');
    }

    public function destroy(Project $project, Bid $bid)
    {
        // Ensure the authenticated user is the owner of the bid
        if ($bid->user_id !== auth()->id()) {
            abort(403);
        }

        $bid->delete();
        return redirect()->route('bids.index')
                         ->with('success', 'Your bid has been successfully canceled.');
    }
}
