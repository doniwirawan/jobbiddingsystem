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

    <!-- Search Form -->
    <form method="GET" action="{{ url('/projects') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <select name="entity" class="form-select">
                    <option value="">All Entities</option>
                    <option value="Corp" {{ request('entity') == 'Corp' ? 'selected' : '' }}>Corp</option>
                    <option value="Weddings" {{ request('entity') == 'Weddings' ? 'selected' : '' }}>Weddings</option>
                    <option value="Studio" {{ request('entity') == 'Studio' ? 'selected' : '' }}>Studio</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Photography/Videography</option>
                    <option value="Photography" {{ request('type') == 'Photography' ? 'selected' : '' }}>Photography</option>
                    <option value="Videography" {{ request('type') == 'Videography' ? 'selected' : '' }}>Videography</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="Primary" {{ request('role') == 'Primary' ? 'selected' : '' }}>Primary</option>
                    <option value="Secondary" {{ request('role') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                </select>
            </div>
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary w-100 me-2">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ url('/projects') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </div>
    </form>

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
