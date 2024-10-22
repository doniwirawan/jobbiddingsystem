@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Dashboard Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
        <!-- Quick Actions Section -->
    @hasanyrole('producer|admin')
    <div class="d-flex justify-content-end mb-4">
        <a href="{{ route('projects.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle-fill"></i> Add New Project
        </a>
    </div>
    @endhasanyrole
    </div>

    <!-- Welcome Message -->
    <div class="alert alert-primary">
        <i class="bi bi-person-circle"></i> Welcome to the Admin Dashboard! Here you can manage users, projects, and bids.
    </div>

    

    <!-- Stats and Quick Access Cards -->
    <div class="row">
        <!-- Total Projects Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-briefcase-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Total Projects</h5>
                    <h3>{{ \App\Models\Project::count() }}</h3>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-light btn-sm mt-3">View Projects</a>
                </div>
            </div>
        </div>

        <!-- Total Bids Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-hand-thumbs-up-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Total Bids</h5>
                    <h3>{{ \App\Models\Bid::count() }}</h3>
                    <a href="{{ route('admin.bids.index') }}" class="btn btn-light btn-sm mt-3">View Bids</a>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    <h5 class="card-title mt-3">Total Users</h5>
                    <h3>{{ \App\Models\User::count() }}</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm mt-3">View Users</a>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Recent Activities Section -->
    <div class="mt-5">
        <h4 class="fw-bold mb-4"><i class="bi bi-clock-history"></i> Recent Activities</h4>

        <!-- Recent Projects -->
        <div class="mb-3">
            <h5><i class="bi bi-briefcase"></i> Recent Projects</h5>
            @if($recentProjects->isEmpty())
                <p class="text-muted">No recent projects.</p>
            @else
                <ul class="list-group">
                    @foreach($recentProjects as $project)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $project->name }}</strong> - {{ $project->date }} ({{ $project->entity }})
                            </div>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">View</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Bids -->
        <div class="mb-3">
            <h5><i class="bi bi-hand-thumbs-up"></i> Recent Bids</h5>
            @if($recentBids->isEmpty())
                <p class="text-muted">No recent bids.</p>
            @else
                <ul class="list-group">
                    @foreach($recentBids as $bid)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                Bid by <strong>{{ $bid->user->name }}</strong> on <strong>{{ $bid->project->name }}</strong> - ${{ number_format($bid->amount, 2) }}
                            </div>
                            <a href="{{ route('admin.projects.bids', $bid->project->id) }}" class="btn btn-sm btn-info">View Bids</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Recent Users -->
        <div class="mb-3">
            <h5><i class="bi bi-person "></i> Recent Users</h5>
            @if($recentUsers->isEmpty())
                <p class="text-muted">No recent users.</p>
            @else
                <ul class="list-group">
                    @foreach($recentUsers as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user->name }}</strong> - {{ $user->email }} (Role: {{ implode(', ', $user->roles->pluck('name')->toArray()) }})
                            </div>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection