@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Bids</h2>

    @if($bids->isEmpty())
        <p class="alert alert-info">You have not placed any bids yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Bid Amount</th>
                    <th>Remarks</th>
                    <th>Project Status</th>
                    <th>Date of Bid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                <tr>
                    <td><a href="{{ route('projects.show', $bid->project->id) }}">{{ $bid->project->name }}</a></td>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>{{ $bid->remarks ?? 'No remarks' }}</td>
                    <td>{{ $bid->project->status }}</td>
                    <td>{{ $bid->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
