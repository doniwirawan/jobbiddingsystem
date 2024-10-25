@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-list-check"></i> My Bids History</h2>

    @if($bids->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> You have not placed any bids on this project.
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Bid Amount</th>
                    <th>Remarks</th>
                    <th>Placed On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                <tr>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>{{ $bid->remarks }}</td>
                    <td>{{ $bid->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        @if($bid->is_winner)
                            <span class="badge bg-success">Won</span>
                        @elseif($bid->project->status === 'Closed')
                            <span class="badge bg-danger">Lost</span>
                        @else
                            <span class="badge bg-info">Active</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
