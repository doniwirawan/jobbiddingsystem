@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Bids for Project: {{ $project->name }}</h2>

    @if($bids->isEmpty())
        <p class="alert alert-info">No bids have been placed on this project yet.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Freelancer</th>
                    <th>Bid Amount</th>
                    <th>Remarks</th>
                    <th>Date of Bid</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bids as $bid)
                <tr class="{{ $bid->id == $lowestBid->id ? 'table-success' : '' }}">
                    <td>{{ $bid->user->name }}</td>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>{{ $bid->remarks ?? 'No remarks' }}</td>
                    <td>{{ $bid->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if ($bid->is_winner)
                            @if ($bid->is_accepted === null)
                                <span class="badge bg-warning">Pending Acceptance</span>
                            @elseif ($bid->is_accepted)
                                <span class="badge bg-success">Accepted</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Other Bid</span>
                        @endif
                    </td>
                    <td>
                        <!-- Mark as Winner Button (Triggers Modal) -->
                        @if(!$bid->is_winner)
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#markWinnerModal" data-bid-id="{{ $bid->id }}"
                                    data-bidder-name="{{ $bid->user->name }}">
                                Mark as Winner
                            </button>
                        @endif

                        <!-- Email Freelancer Button -->
                        <a href="mailto:{{ $bid->user->email }}?subject=Bid Status for Project: {{ $project->name }}" class="btn btn-sm btn-info">
                            Email Freelancer
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Mark as Winner Confirmation Modal -->
<div class="modal fade" id="markWinnerModal" tabindex="-1" aria-labelledby="markWinnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markWinnerModalLabel">Confirm Winner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark <strong><span id="bidderName"></span></strong>'s bid as the winning bid?
            </div>
            <div class="modal-footer">
                <form id="markWinnerForm" method="POST" action="">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Yes, Mark as Winner</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Show modal with specific bid data
    var markWinnerModal = document.getElementById('markWinnerModal');
    markWinnerModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var bidId = button.getAttribute('data-bid-id');
        var bidderName = button.getAttribute('data-bidder-name');

        // Update modal content
        var modalBidderName = markWinnerModal.querySelector('#bidderName');
        modalBidderName.textContent = bidderName;

        // Update form action URL
        var form = markWinnerModal.querySelector('#markWinnerForm');
        form.action = `/admin/projects/{{ $project->slug }}/bids/${bidId}/winner`;
    });

    // Optional: Log data for debugging purposes
    function logFormData(projectSlug, bidId) {
        console.log('Project Slug:', projectSlug);
        console.log('Bid ID:', bidId);
    }
</script>
@endsection
