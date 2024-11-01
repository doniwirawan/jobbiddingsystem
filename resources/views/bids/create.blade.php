@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-hand-thumbs-up-fill"></i> Place Your Bid</h2>
        <a href="{{ route('projects.show', $project->slug) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Project
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="fw-bold"><i class="bi bi-briefcase-fill"></i> Project: {{ $project->name }}</h4>
            <p class="mb-4">
                <strong><i class="bi bi-calendar-event"></i> Start Date:</strong> 
                {{ $project->start_date ? $project->start_date->format('Y-m-d') : 'Not specified' }}<br>
                <strong><i class="bi bi-calendar-event"></i> End Date:</strong> 
                {{ $project->end_date ? $project->end_date->format('Y-m-d') : 'Not specified' }}<br>
                <strong><i class="bi bi-camera-video-fill"></i> Type:</strong> {{ ucfirst($project->type) }}<br>
                <strong><i class="bi bi-currency-dollar"></i> Rate:</strong> ${{ number_format($project->rate, 2) }}
            </p>

            <form action="{{ route('bids.store', $project->slug) }}" method="POST">
                @csrf

                <!-- Bid Amount Input -->
                <div class="mb-3">
                    <label for="amount" class="form-label"><i class="bi bi-cash"></i> Bid Amount</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                               id="amount" name="amount" placeholder="Enter your bid amount"
                               value="{{ old('amount', $existingBid->amount ?? '') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Remarks Input -->
                <div class="mb-3">
                    <label for="remarks" class="form-label"><i class="bi bi-pencil"></i> Remarks (optional)</label>
                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks"
                              rows="4" placeholder="Add any additional comments...">{{ old('remarks', $existingBid->remarks ?? '') }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success"><i class="bi bi-hand-thumbs-up"></i> Submit Bid</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
