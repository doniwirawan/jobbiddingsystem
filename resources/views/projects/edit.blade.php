@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-pencil-square"></i> Edit Project</h2>

        <!-- Button to view all projects -->
        <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-task"></i> View All Projects
        </a>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Edit Project Form -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('projects.update', $project->slug) }}" method="POST">
                @csrf
                @method('PATCH') <!-- Required for updating a resource -->

                <!-- Project Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $project->name) }}" placeholder="Enter project name" required>
                </div>

                {{-- <!-- Project Date -->
                <div class="mb-3">
                    <label for="date" class="form-label">Project Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $project->date) }}" required>
                </div> --}}
                <!-- Project Date -->
                <div class="mb-3">
                    <label for="start_date" class="form-label">Project Start Date</label>
                    <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $project->start_date ?? '') }}"  required>
                </div>
                 <div class="mb-3">
                    <label for="end_date" class="form-label">Project End Date</label>
                    <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $project->end_date ?? '') }}"  required>
                </div>

                <!-- Entity -->
                <div class="mb-3">
                    <label for="entity" class="form-label">Entity</label>
                    <select class="form-select" id="entity" name="entity" required>
                        <option value="Corp" {{ $project->entity == 'Corp' ? 'selected' : '' }}>Corp</option>
                        <option value="Weddings" {{ $project->entity == 'Weddings' ? 'selected' : '' }}>Weddings</option>
                        <option value="Studio" {{ $project->entity == 'Studio' ? 'selected' : '' }}>Studio</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="Photography" {{ $project->type == 'Photography' ? 'selected' : '' }}>Photography</option>
                        <option value="Videography" {{ $project->type == 'Videography' ? 'selected' : '' }}>Videography</option>
                    </select>
                </div>

                <!-- Rate -->
                <div class="mb-3">
                    <label for="rate" class="form-label">Rate</label>
                    <input type="number" class="form-control" id="rate" name="rate" value="{{ old('rate', $project->rate) }}" step="0.01" placeholder="Enter rate" required>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Primary" {{ $project->role == 'Primary' ? 'selected' : '' }}>Primary</option>
                        <option value="Secondary" {{ $project->role == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                    </select>
                </div>

                <!-- Remarks -->
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Any remarks">{{ old('remarks', $project->remarks) }}</textarea>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Open" {{ $project->status == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="Closed" {{ $project->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
