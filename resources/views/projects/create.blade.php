@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Project</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Project Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Project Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>

        <div class="mb-3">
            <label for="entity" class="form-label">Entity</label>
            <select class="form-select" id="entity" name="entity" required>
                <option value="Corp">Corp</option>
                <option value="Weddings">Weddings</option>
                <option value="Studio">Studio</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" id="type" name="type" required>
                <option value="Photography">Photography</option>
                <option value="Videography">Videography</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="rate" class="form-label">Rate</label>
            <input type="number" class="form-control" id="rate" name="rate" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="Primary">Primary</option>
                <option value="Secondary">Secondary</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Open">Open</option>
                <option value="Closed">Closed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Project</button>
    </form>
</div>
@endsection
