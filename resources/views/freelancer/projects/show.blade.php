@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Project Details Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-info-circle-fill"></i> Project Details</h2>

        <!-- Back Button -->
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Projects
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Project Name and Info -->
            <h3 class="card-title"><i class="bi bi-briefcase-fill"></i> {{ $project->name }}</h3>
            <p class="card-text">
                {{-- <strong><i class="bi bi-calendar-event"></i> Project Date:</strong> {{ $project->date }}<br> --}}
                <strong><i class="bi bi-calendar-event"></i> Start Date:</strong> 
    {{ $project->start_date ? $project->start_date->format('Y-m-d') : 'Not specified' }}
    <br>
    <strong><i class="bi bi-calendar-event"></i> End Date:</strong> 
    {{ $project->end_date ? $project->end_date->format('Y-m-d') : 'Not specified' }}
    <br>
                <strong><i class="bi bi-building"></i> Entity:</strong> {{ ucfirst($project->entity) }}<br>
                <strong><i class="bi bi-camera-video-fill"></i> Type:</strong> {{ $project->type }}<br>
                <strong><i class="bi bi-currency-dollar"></i> Rate:</strong> ${{ number_format($project->rate, 2) }}<br>
                <strong><i class="bi bi-person-badge-fill"></i> Role:</strong> {{ ucfirst($project->role) }}<br>
                <strong><i class="bi bi-card-text"></i> Remarks:</strong> {{ $project->remarks }}
            </p>

            <!-- Conditional buttons for freelancers and project management -->
            <div class="mt-4 d-flex justify-content-between">
                <!-- Show bidding option for freelancers -->
                @if(Auth::check())
                    @role('freelancer')
                        <a href="{{ route('bids.create', $project->slug) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-hand-thumbs-up"></i> Place Bid
                        </a>

                        <!-- View My Bids for this project (Freelancer) -->
                        @if($userBid)
                            <a href="{{ route('bids.history', ['project' => $project->slug, 'user' => auth()->id()]) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-list-check"></i> View My Bids
                            </a>
                        @endif
                    @endrole
                @else
                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> Login to Bid
                    </a>
                @endif

                <!-- Admin/Producer: Project Management Buttons -->
                @hasanyrole('producer|admin')
                    <div>
                        <!-- Edit Project Button -->
                        <a href="{{ route('projects.edit', $project->slug) }}" class="btn btn-warning me-2">
                            <i class="bi bi-pencil-fill"></i> Edit Project
                        </a>

                        <!-- Delete Project Button -->
                        <form action="{{ route('projects.delete', $project->slug) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to delete this project?');">
                                <i class="bi bi-trash-fill"></i> Delete Project
                            </button>
                        </form>

                        <!-- Open/Close Project Button -->
                        @if($project->status === 'Open')
                            <form action="{{ route('projects.close', $project->slug) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning me-2">
                                    <i class="bi bi-x-circle"></i> Close Project
                                </button>
                            </form>
                        @else
                            <form action="{{ route('projects.open', $project->slug) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-check-circle"></i> Open Project
                                </button>
                            </form>
                        @endif

                        <!-- View Bidders Button (Admin and Producer) -->
                        <a href="{{ route('admin.projects.bids', $project->slug) }}" class="btn btn-info">
                            <i class="bi bi-person-lines-fill"></i> View Bidders
                        </a>
                    </div>
                @endhasanyrole
            </div>
        </div>
    </div>

    <!-- Show bidding status (for freelancers) -->
    @role('freelancer')
    @if ($winningBid)
        <div class="alert alert-success mt-4">
            <i class="bi bi-award-fill"></i> Congratulations! You won the bid with an amount of ${{ number_format($winningBid->amount, 2) }}.
        </div>
    @elseif ($userBid)
        <div class="alert alert-info mt-4">
            <i class="bi bi-clock-fill"></i> Your current bid for this project is ${{ number_format($userBid->amount, 2) }}. The project is still open for bidding.
        </div>
    @endif
    @endrole
</div>
@endsection
