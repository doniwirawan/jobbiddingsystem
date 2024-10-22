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
                    <td>{{ $project->date }}</td>
                    <td>{{ $project->entity }}</td>
                    <td>{{ $project->type }}</td>
                    <td>${{ number_format($project->rate, 2) }}</td>
                    <td>
                        @if($project->status === 'Open')
                            <span class="badge bg-success">Open</span>
                        @else
                            <span class="badge bg-danger">Closed</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <!-- View Button -->
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info me-2">
                            <i class="bi bi-eye-fill"></i> View
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('projects.delete', $project->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger me-2" onclick="return confirm('Are you sure you want to delete this project?')">
                                <i class="bi bi-trash-fill"></i> Delete
                            </button>
                        </form>

                        <!-- Status Toggle Buttons -->
                        @if($project->status === 'Open')
                            <form action="{{ route('projects.close', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning">
                                    <i class="bi bi-x-circle-fill"></i> Close
                                </button>
                            </form>
                        @else
                            <form action="{{ route('projects.open', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle-fill"></i> Open
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
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
