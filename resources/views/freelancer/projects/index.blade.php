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
                            <!-- View Details Button -->
                            <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            
                            <!-- Show bidding option for freelancers -->
                            @if(Auth::check())
                                @role('freelancer')
                                    <a href="{{ route('bids.create', $project->slug) }}" class="btn btn-success btn-sm">
                                        <i class="bi bi-hand-thumbs-up"></i> Place Bid
                                    </a>
                                @endrole
                            @else
                                <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-box-arrow-in-right"></i> Login to Bid
                                </a>
                            @endif

                            <!-- Admin/Producer: Dropdown Button for Actions -->
                            @hasanyrole('admin|producer')
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i> Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <!-- View Bidders Button -->
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.projects.bids', $project->slug) }}">
                                            <i class="bi bi-person-lines-fill"></i> View Bidders
                                        </a>
                                    </li>

                                    <!-- Toggle Open/Close -->
                                    <li>
                                        @if($project->status === 'Open')
                                            <form action="{{ route('projects.close', $project->slug) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-warning">
                                                    <i class="bi bi-x-circle"></i> Close Project
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('projects.open', $project->slug) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item text-success">
                                                    <i class="bi bi-check-circle"></i> Open Project
                                                </button>
                                            </form>
                                        @endif
                                    </li>

                                    <!-- Edit Button -->
                                    <li>
                                        <a class="dropdown-item text-info" href="{{ route('projects.edit', $project->slug) }}">
                                            <i class="bi bi-pencil"></i> Edit Project
                                        </a>
                                    </li>

                                    <!-- Delete Button -->
                                    <li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $project->slug }}">
                                            <i class="bi bi-trash"></i> Delete Project
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal-{{ $project->slug }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete Project</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the project "{{ $project->name }}"? This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('projects.delete', $project->slug) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
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
