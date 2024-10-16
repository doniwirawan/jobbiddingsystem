@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-folder-fill"></i> Available Projects</h2>
        
        <!-- Create new project button (for producers/admins) -->
        @hasanyrole('producer|admin')
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Project
            </a>
        @endhasanyrole
    </div>

    <!-- Check if there are any projects -->
    @if($projects->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> No projects available at the moment. Please check back later!
        </div>
    @else
        <!-- Display a grid/list of projects -->
        <div class="row">
            @foreach ($projects as $project)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bi bi-briefcase-fill"></i> {{ $project->name }}
                        </h5>
                        <p class="card-text">
                            <strong><i class="bi bi-calendar-event"></i> Project Date:</strong> {{ $project->date }}<br>
                            <strong><i class="bi bi-building"></i> Entity:</strong> {{ ucfirst($project->entity) }}<br>
                            <strong><i class="bi bi-camera-video-fill"></i> Type:</strong> {{ $project->type }}<br>
                            <strong><i class="bi bi-currency-dollar"></i> Rate:</strong> ${{ number_format($project->rate, 2) }}<br>
                            <strong><i class="bi bi-person-badge-fill"></i> Role:</strong> {{ ucfirst($project->role) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            <!-- Show bidding option for freelancers -->
                            @role('freelancer')
                                <a href="{{ route('bids.create', $project->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-hand-thumbs-up"></i> Place Bid
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination (if necessary) -->
        <div class="d-flex justify-content-center">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection
