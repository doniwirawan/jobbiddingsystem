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
                        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-sm btn-info me-2">
                            <i class="bi bi-eye-fill"></i> View
                        </a>

                        <!-- Delete Button (opens modal) -->
                        <button type="button" class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteModal" data-project-slug="{{ $project->slug }}" data-project-name="{{ $project->name }}">
                            <i class="bi bi-trash-fill"></i> Delete
                        </button>

                        <!-- Status Toggle Buttons (Open/Close) -->
                        @if($project->status === 'Open')
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#closeModal" data-project-slug="{{ $project->slug }}" data-project-name="{{ $project->name }}">
                                <i class="bi bi-x-circle-fill"></i> Close
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#openModal" data-project-slug="{{ $project->slug }}" data-project-name="{{ $project->name }}">
                                <i class="bi bi-check-circle-fill"></i> Open
                            </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the project <strong><span id="modalProjectName"></span></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteProjectForm" method="POST" action="">
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
<div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="closeModalLabel">Confirm Close Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to close the project <strong><span id="modalCloseProjectName"></span></strong>?
            </div>
            <div class="modal-footer">
                <form id="closeProjectForm" method="POST" action="">
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
<div class="modal fade" id="openModal" tabindex="-1" aria-labelledby="openModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="openModalLabel">Confirm Open Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to open the project <strong><span id="modalOpenProjectName"></span></strong>?
            </div>
            <div class="modal-footer">
                <form id="openProjectForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Yes, Open</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Handle delete project modal
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var projectSlug = button.getAttribute('data-project-slug');
        var projectName = button.getAttribute('data-project-name');
        var modalProjectName = deleteModal.querySelector('#modalProjectName');
        modalProjectName.textContent = projectName;
        var form = deleteModal.querySelector('#deleteProjectForm');
        form.action = '/projects/' + projectSlug;
    });

    // Handle close project modal
    var closeModal = document.getElementById('closeModal');
    closeModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var projectSlug = button.getAttribute('data-project-slug');
        var projectName = button.getAttribute('data-project-name');
        var modalCloseProjectName = closeModal.querySelector('#modalCloseProjectName');
        modalCloseProjectName.textContent = projectName;
        var form = closeModal.querySelector('#closeProjectForm');
        form.action = '/projects/' + projectSlug + '/close';
    });

    // Handle open project modal
    var openModal = document.getElementById('openModal');
    openModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var projectSlug = button.getAttribute('data-project-slug');
        var projectName = button.getAttribute('data-project-name');
        var modalOpenProjectName = openModal.querySelector('#modalOpenProjectName');
        modalOpenProjectName.textContent = projectName;
        var form = openModal.querySelector('#openProjectForm');
        form.action = '/projects/' + projectSlug + '/open';
    });
</script>
@endsection

