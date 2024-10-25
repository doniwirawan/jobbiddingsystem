@extends('layouts.app')

@section('title', 'Unauthorized Access')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-4 text-danger"><i class="bi bi-shield-exclamation"></i> 403</h1>
    <h2 class="mb-4">Unauthorized Access</h2>
    <p class="lead">Sorry, you do not have permission to access this page.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Go Back
    </a>
    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
        <i class="bi bi-house-door"></i> Return to Dashboard
    </a>
</div>
@endsection
