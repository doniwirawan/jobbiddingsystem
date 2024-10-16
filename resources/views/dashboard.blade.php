@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-speedometer2"></i> Dashboard</h2>
    
    @role('admin')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill fs-1 text-primary"></i>
                    <h3>{{ $userCount }}</h3>
                    <p>Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-briefcase-fill fs-1 text-success"></i>
                    <h3>{{ $projectCount }}</h3>
                    <p>Total Projects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text-fill fs-1 text-warning"></i>
                    <h3>{{ $bidCount }}</h3>
                    <p>Total Bids</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Admin Quick Actions</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary"><i class="bi bi-people"></i> Manage Users</a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-success"><i class="bi bi-briefcase"></i> Manage Projects</a>
        <a href="{{ route('admin.bids.index') }}" class="btn btn-warning"><i class="bi bi-file-earmark-text"></i> View Bids</a>
    </div>
    @endrole

    @role('producer')
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-briefcase-fill fs-1 text-success"></i>
                    <h3>{{ $projectCount }}</h3>
                    <p>Projects Created</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text-fill fs-1 text-warning"></i>
                    <h3>{{ $totalBidsReceived }}</h3>
                    <p>Total Bids Received</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Producer Quick Actions</h4>
        <a href="{{ route('projects.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Create New Project</a>
        <a href="{{ route('projects.index') }}" class="btn btn-success"><i class="bi bi-briefcase"></i> Manage Projects</a>
    </div>
    @endrole

    @role('freelancer')
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-hand-thumbs-up-fill fs-1 text-success"></i>
                    <h3>{{ $bidCount }}</h3>
                    <p>Bids Placed</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="bi bi-award-fill fs-1 text-warning"></i>
                    <h3>{{ $projectsWon }}</h3>
                    <p>Projects Won</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Freelancer Quick Actions</h4>
        <a href="{{ route('projects.index') }}" class="btn btn-primary"><i class="bi bi-briefcase"></i> View Available Projects</a>
        <a href="{{ route('bids.index') }}" class="btn btn-success"><i class="bi bi-hand-thumbs-up"></i> My Bids</a>
    </div>
    @endrole
</div>
@endsection
