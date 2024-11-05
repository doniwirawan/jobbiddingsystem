<?php

namespace App\Http\Controllers;

use App\Models\{Project, Bid};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\{BidAccepted, BidWonNotification};

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
        // Check if the user is the bid winner and the bid hasn't already been accepted or rejected
        // if (auth()->id() !== $bid->user_id || $bid->is_accepted !== null) {
        //     abort(403); // Unauthorized action
        // }
        if (auth()->id() !== $bid->user_id) {
            abort(403, 'You do not own this bid.');
        }

        // Mark the bid as accepted and update the timestamp
        $bid->update([
            'is_accepted' => true,
            'accepted_at' => now(),
        ]);

        // Automatically close the project if this bid is accepted
        $project = $bid->project;
        $project->update([
            'status' => 'Closed',
        ]);

         // Send notifications to the bidder and the project creator
        $bid->user->notify(new BidAccepted($project));
        // $project->creator->notify((new BidAccepted($project))->cc('doni@studiofivecorp.com'));

        return redirect()->back()->with('success', 'You have accepted the bid and the project is now closed.');
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

    // Renamed: Accept a bid
    public function acceptBid(Bid $bid)
    {
        // Ensure only the bid owner can accept
        if (Auth::id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403); // Unauthorized
        }

        // Update the bid to mark it as accepted
        $bid->update([
            'is_accepted' => true,
            'accepted_at' => now(),
        ]);

        // Optionally close the project
        $project = $bid->project;
        $project->update([
            'status' => 'Closed',
        ]);

        return redirect()->route('bids.index')->with('success', 'Bid accepted and project closed.');
    }

    // Renamed: Reject a bid
    public function rejectBid(Bid $bid)
    {
        // Ensure only the bid owner can reject
        if (Auth::id() !== $bid->user_id || $bid->is_accepted !== null) {
            abort(403); // Unauthorized
        }

        // Update the bid to mark it as rejected
        $bid->update([
            'is_accepted' => false,
        ]);

        return redirect()->route('bids.index')->with('success', 'Bid rejected.');
    }
}
