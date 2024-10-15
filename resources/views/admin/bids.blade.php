@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bids for Project: {{ $project->name }}</h2>

    <!-- Success message for bid selection -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Freelancer</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bids as $bid)
            <tr>
                <td>{{ $bid->user->name }}</td>
                <td>${{ number_format($bid->amount, 2) }}</td>
                <td>{{ $bid->remarks ?? 'N/A' }}</td>
                <td>
                    @if ($bid->is_winner)
                        <span class="badge bg-success">Winner</span>
                    @else
                        <span class="badge bg-secondary">Pending</span>
                    @endif
                </td>
                <td>
                    @if (!$bid->is_winner)
                        <!-- Mark as winner form -->
                        <form action="{{ route('admin.projects.markWinner', ['project' => $project->id, 'bid' => $bid->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Mark as Winner</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
