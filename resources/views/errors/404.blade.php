@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-warning"><i class="bi bi-exclamation-triangle"></i> 404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="lead">Sorry, the page you are looking for could not be found.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
        <i class="bi bi-house-door"></i> Return to Dashboard
    </a>
</div>
@endsection
