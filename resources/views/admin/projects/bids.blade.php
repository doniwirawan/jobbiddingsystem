@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Bids for Project: {{ $project->name }}</h2>

    @if($bids->isEmpty())
        <p class="alert alert-info">No bids have been placed on this project yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Freelancer</th>
                    <th>Bid Amount</th>
                    <th>Remarks</th>
                    <th>Date of Bid</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for marking the winner -->
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                <tr class="{{ $bid->id == $lowestBid->id ? 'table-success' : '' }}">
                    <td>{{ $bid->user->name }}</td>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>{{ $bid->remarks ?? 'No remarks' }}</td>
                    <td>{{ $bid->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if ($bid->id == $lowestBid->id)
                            <span class="badge bg-success">Lowest Bid (Winner)</span>
                        @else
                            <span class="badge bg-secondary">Other Bid</span>
                        @endif
                    </td>
                    <td>
                         <!-- Mark as Winner Button -->
                        @if(!$bid->is_winner)
                            <form action="{{ route('admin.projects.markWinner', [$project->id, $bid->id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to mark this bid as the winner?')">Mark as Winner</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
