@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-folder-plus"></i> Create New Project</h2>

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

    <!-- Create Project Form -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf

                <!-- Project Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Project Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter project name" required>
                </div>

                <!-- Project Date -->
                <div class="mb-3">
                    <label for="date" class="form-label">Project Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <!-- Entity -->
                <div class="mb-3">
                    <label for="entity" class="form-label">Entity</label>
                    <select class="form-select" id="entity" name="entity" required>
                        <option value="Corp">Corp</option>
                        <option value="Weddings">Weddings</option>
                        <option value="Studio">Studio</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="Photography">Photography</option>
                        <option value="Videography">Videography</option>
                    </select>
                </div>

                <!-- Rate -->
                <div class="mb-3">
                    <label for="rate" class="form-label">Rate</label>
                    <input type="number" class="form-control" id="rate" name="rate" placeholder="Enter rate" step="0.01" required>
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Primary">Primary</option>
                        <option value="Secondary">Secondary</option>
                    </select>
                </div>

                <!-- Remarks -->
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Any remarks"></textarea>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Open">Open</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Create Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
