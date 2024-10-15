@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Available Projects</h2>

    <form method="GET" action="{{ route('projects.index') }}" class="row mb-4">
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
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    @if($projects->isEmpty())
        <div class="alert alert-warning">No projects found.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Entity</th>
                    <th>Type</th>
                    <th>Rate</th>
                    <th>Role</th>
                    <th>Remarks</th>
                    <th>Details</th>
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
                    <td>{{ $project->role }}</td>
                    <td>
                        <span class="badge bg-{{ $project->status === 'Open' ? 'success' : 'danger' }}">
                            {{ $project->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info btn-sm">View</a>
                        @if($project->status === 'Open')
                            <a href="{{ route('bids.create', $project->id) }}" class="btn btn-primary btn-sm">Bid</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $projects->links() }} <!-- Pagination links -->
        </div>
    @endif
</div>
@endsection
