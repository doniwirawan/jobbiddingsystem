{{-- @extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-list-check"></i> My Bids</h2>

    <!-- Check if the user has any bids -->
    @if($bids->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> You haven't placed any bids yet.
        </div>
    @else
        <!-- Display Bids in a Table Format -->
        <table class="table table-hover mt-4 shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th scope="col"><i class="bi bi-briefcase-fill"></i> Project Name</th>
                    <th scope="col"><i class="bi bi-cash"></i> Your Bid Amount</th>
                    <th scope="col"><i class="bi bi-award-fill"></i> Bid Status</th>
                    <th scope="col"><i class="bi bi-calendar-event"></i> Project Date</th>
                    <th scope="col"><i class="bi bi-gear"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                    <tr>
                        <td>{{ $bid->project->name }}</td>
                        <td>${{ number_format($bid->amount, 2) }}</td>
                        
                        <!-- Bid Status: Won, Pending, or Lost -->
                        <td>
                            @if ($bid->is_winner)
                                <span class="badge bg-success"><i class="bi bi-trophy-fill"></i> Won</span>
                            @elseif ($bid->project->status == 'closed')
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Lost</span>
                            @else
                                <span class="badge bg-warning"><i class="bi bi-clock-fill"></i> Pending</span>
                            @endif
                        </td>

                        <td>{{ $bid->project->date }}</td>

                        <!-- Action Buttons -->
                        <td>
                            <a href="{{ route('projects.show', $bid->project->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View Project
                            </a>
                            @if (!$bid->is_winner && $bid->project->status != 'closed')
                                <a href="{{ route('bids.create', $bid->project->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil-square"></i> Edit Bid
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $bids->links() }}
        </div>
    @endif
</div>
@endsection --}}

{{-- 
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-list-check"></i> My Bids</h2>

    <!-- Check if the user has any bids -->
    @if($bids->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> You haven't placed any bids yet.
        </div>
    @else
        <!-- Display Bids in a Table Format -->
        <table class="table table-hover mt-4 shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th scope="col"><i class="bi bi-briefcase-fill"></i> Project Name</th>
                    <th scope="col"><i class="bi bi-cash"></i> Your Bid Amount</th>
                    <th scope="col"><i class="bi bi-award-fill"></i> Bid Status</th>
                    <th scope="col"><i class="bi bi-calendar-event"></i> Project Date</th>
                    <th scope="col"><i class="bi bi-gear"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                    <tr>
                        <td>{{ $bid->project->name }}</td>
                        <td>${{ number_format($bid->amount, 2) }}</td>
                        
                        <!-- Bid Status: Won, Pending, or Lost -->
                        <td>
                            @if ($bid->is_winner)
                                <span class="badge bg-success"><i class="bi bi-trophy-fill"></i> Won</span>
                            @elseif ($bid->project->status == 'closed')
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Lost</span>
                            @else
                                <span class="badge bg-warning"><i class="bi bi-clock-fill"></i> Pending</span>
                            @endif
                        </td>

                        <td>{{ $bid->project->date }}</td>

                        <!-- Action Buttons -->
                        <td>
                            <a href="{{ route('projects.show', $bid->project->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View Project
                            </a>
                            
                            @if (!$bid->is_winner && $bid->project->status != 'closed')
                                <a href="{{ route('bids.create', $bid->project->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil-square"></i> Edit Bid
                                </a>
                            @endif

                            <!-- Show Accept/Reject Buttons for Won Bids -->
                            @if ($bid->is_winner && $bid->is_accepted === null)
                                <form action="{{ route('bids.accept', $bid->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check"></i> Accept
                                    </button>
                                </form>

                                <form action="{{ route('bids.reject', $bid->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
                                </form>
                            @elseif ($bid->is_accepted)
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Accepted</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Rejected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $bids->links() }}
        </div>
    @endif
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-list-check"></i> My Bids</h2>

    <!-- Check if the user has any bids -->
    @if($bids->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> You haven't placed any bids yet.
        </div>
    @else
        <!-- Display Bids in a Table Format -->
        <table class="table table-hover mt-4 shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th scope="col"><i class="bi bi-briefcase-fill"></i> Project Name</th>
                    <th scope="col"><i class="bi bi-cash"></i> Your Bid Amount</th>
                    <th scope="col"><i class="bi bi-award-fill"></i> Bid Status</th>
                    <th scope="col"><i class="bi bi-calendar-event"></i> Project Start Date</th><th scope="col"><i class="bi bi-calendar-event"></i> Project End Date</th>
                    <th scope="col"><i class="bi bi-gear"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                    <tr>
                        <td>{{ $bid->project->name }}</td>
                        <td>${{ number_format($bid->amount, 2) }}</td>
                        
                        <!-- Bid Status: Won, Pending, or Lost -->
                        <td>
                            @if ($bid->is_winner)
                                @if ($bid->is_accepted === null)
                                    <span class="badge bg-warning"><i class="bi bi-clock-fill"></i> Pending Acceptance</span>
                                @elseif ($bid->is_accepted)
                                    <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Accepted</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Rejected</span>
                                @endif
                            @elseif ($bid->project->status == 'closed')
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Lost</span>
                            @else
                                <span class="badge bg-warning"><i class="bi bi-clock-fill"></i> Pending</span>
                            @endif
                        </td>

                        <td>{{ $bid->project->date }}</td>

                        <!-- Action Buttons -->
                        <td>
                            <a href="{{ route('projects.show', $bid->project->slug) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> View Project
                            </a>
                            
                            @if (!$bid->is_winner && $bid->project->status != 'closed')
                                <a href="{{ route('bids.create', $bid->project->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil-square"></i> Edit Bid
                                </a>
                            @endif

                            <!-- Show Accept/Reject Buttons for Won Bids -->
                            @if ($bid->is_winner && $bid->is_accepted === null)
                                <form action="{{ route('bids.accept', $bid->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check"></i> Accept
                                    </button>
                                </form>

                                <form action="{{ route('bids.reject', $bid->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
                                </form>
                            @elseif ($bid->is_accepted)
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Accepted</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle-fill"></i> Rejected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $bids->links() }}
        </div>
    @endif
</div>
@endsection
