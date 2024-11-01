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
                    <th>Actions</th>
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
                        @if ($bid->is_winner)
                            @if ($bid->is_accepted === null)
                                <span class="badge bg-warning">Pending Acceptance</span>
                            @elseif ($bid->is_accepted)
                                <span class="badge bg-success">Accepted</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Other Bid</span>
                        @endif
                    </td>
                    <td>
                        <!-- Mark as Winner Button triggers Modal -->
                        @if(!$bid->is_winner)
                            <form method="POST" action="{{ route('admin.projects.markWinner', ['project' => $project->slug, 'bid' => $bid->id]) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Mark as Winner
                                </button>
                            </form>
                        @endif

                        <!-- Email Freelancer Button -->
                        <a href="mailto:{{ $bid->user->email }}?subject=Bid Status for Project: {{ $project->name }}" class="btn btn-sm btn-info">
                            Email Freelancer
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
