@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard</h2>

    <p>Welcome to your dashboard, {{ Auth::user()->name }}!</p>

    <!-- Example: Links to manage projects -->
    <div>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create New Project</a>
        <a href="{{ route('projects.index') }}" class="btn btn-secondary">View All Projects</a>
    </div>
</div>
@endsection
