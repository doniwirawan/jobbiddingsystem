@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome to the Admin Dashboard! Here you can manage users, projects, and bids.</p>

    <!-- Example section for statistics or quick actions -->
    <div class="row">
        <!-- Total Projects Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Projects</div>
                <div class="card-body">
                    <h4>{{ \App\Models\Project::count() }}</h4>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-primary btn-sm">View Projects</a>
                </div>
            </div>
        </div>

        <!-- Total Bids Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Bids</div>
                <div class="card-body">
                    <h4>{{ \App\Models\Bid::count() }}</h4>
                    <a href="{{ route('admin.bids.index') }}" class="btn btn-primary btn-sm">View Bids</a>
                </div>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Users</div>
                <div class="card-body">
                    <h4>{{ \App\Models\User::count() }}</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View Users</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
