@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $project->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Project Information</h5>
                            <p><strong>Date:</strong> {{ $project->date }}</p>
                            <p><strong>Entity:</strong> {{ $project->entity }}</p>
                            <p><strong>Type:</strong> {{ $project->type }}</p>
                            <p><strong>Role:</strong> {{ $project->role }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $project->status === 'Open' ? 'success' : 'danger' }}">
                                    {{ $project->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment & Rates</h5>
                            <p><strong>Rate:</strong> ${{ number_format($project->rate, 2) }}</p>
                            <p><strong>Remarks:</strong></p>
                            <p>{{ $project->remarks ?? 'No remarks' }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Back to Projects</a>
                        @if($project->status === 'Open')
                            <a href="{{ route('bids.create', $project->id) }}" class="btn btn-primary">Bid for this Project</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
