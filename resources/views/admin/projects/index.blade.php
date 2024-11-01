@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-briefcase-fill"></i> Manage Projects</h2>

        <!-- Button to create a new project (only for producers and admins) -->
        @hasanyrole('producer|admin')
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Add New Project
        </a>
        @endhasanyrole
    </div>

    @if($projects->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle-fill"></i> No projects found.
        </div>
    @else
        <table class="table table-hover shadow-sm">
            <thead class="table-light">
                <tr>
                    <th><i class="bi bi-briefcase-fill"></i> Name</th>
                    <th><i class="bi bi-calendar3"></i> Date</th>
                    <th><i class="bi bi-building"></i> Entity</th>
                    <th><i class="bi bi-camera-reels-fill"></i> Type</th>
                    <th><i class="bi bi-cash"></i> Rate</th>
                    <th><i class="bi bi-toggle-on"></i> Status</th>
                    <th><i class="bi bi-gear-fill"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->start_date ?? 'N/A' }} - {{ $project->end_date ?? 'N/A' }}</td>
                    <td>{{ $project->entity }}</td>
                    <td>{{ $project->type }}</td>
                    <td>${{ number_format($project->rate, 2) }}</td>
                    <td>
                        <span class="badge {{ $project->status === 'Open' ? 'bg-success' : 'bg-danger' }}">
                            {{ $project->status }}
                        </span>
                    </td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-sm btn-info me-2">
                            <i class="bi bi-eye-fill"></i> View
                        </a>

                        <!-- Delete Button (opens modal) -->
                        <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $project->slug }}">
                            <i class="bi bi-trash-fill"></i> Delete
                        </button>

                        <!-- Status Toggle Buttons (Open/Close) -->
                        @if($project->status === 'Open')
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#closeModal-{{ $project->slug }}">
                                <i class="bi bi-x-circle-fill"></i> Close
                            </button>
                        @else
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#openModal-{{ $project->slug }}">
                                <i class="bi bi-check-circle-fill"></i> Open
                            </button>
                        @endif
                    </td>
                </tr>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal-{{ $project->slug }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete the project <strong>{{ $project->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('admin.projects.destroy', $project->slug) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Close Confirmation Modal -->
                <div class="modal fade" id="closeModal-{{ $project->slug }}" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Close Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to close the project <strong>{{ $project->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('admin.projects.close', $project->slug) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning">Yes, Close</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Open Confirmation Modal -->
                <div class="modal fade" id="openModal-{{ $project->slug }}" tabindex="-1" aria-labelledby="openModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Open Project</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to open the project <strong>{{ $project->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{ route('admin.projects.open', $project->slug) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">Yes, Open</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{-- <div class="d-flex justify-content-center">
            {{ $projects->links() }}
        </div> --}}
    @endif
</div>
@endsection
