{{-- @extends('layouts.app')

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
                            @if ($bid->id == $lowestBid->id)
                                <span class="badge bg-success">Lowest Bid</span>
                            @else
                                <span class="badge bg-secondary">Other Bid</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!$bid->is_winner)
                            <!-- Button to trigger Mark as Winner modal -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#markWinnerModal" data-bid-id="{{ $bid->id }}" data-bidder-name="{{ $bid->user->name }}">
                                Mark as Winner
                            </button>
                        @endif

                        <!-- Button to email the freelancer -->
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

<!-- Mark as Winner Modal -->
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
        form.action = '/admin/projects/{{ $project->slug }}/bids/' + bidId + '/winner';
    });
</script>
@endsection 
 --}}
{{-- 
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
                            @if ($bid->id == $lowestBid->id)
                                <span class="badge bg-success">Lowest Bid</span>
                            @else
                                <span class="badge bg-secondary">Other Bid</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!$bid->is_winner)
                            <!-- Button to trigger Mark as Winner modal -->
                            <button type="button" class="btn btn-sm btn-primary mark-winner-btn"
                                    data-bid-id="{{ $bid->id }}" data-project-slug="{{ $project->slug }}">
                                Mark as Winner
                            </button>
                        @endif

                        <!-- Button to email the freelancer -->
                        <a href="mailto:{{ $bid->user->email }}?subject=Bid Status for Project: {{ $project->name }}"
                           class="btn btn-sm btn-info">
                            Email Freelancer
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Mark as Winner Modal -->
<div class="modal fade" id="markWinnerModal" tabindex="-1" aria-labelledby="markWinnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markWinnerModalLabel">Confirm Winner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this bid as the winner?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success confirm-winner-btn">Yes, Mark as Winner</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let selectedBidId = null;
    let selectedProjectSlug = null;

    // Attach event listener to the Mark as Winner button
    document.querySelectorAll('.mark-winner-btn').forEach(button => {
        button.addEventListener('click', function () {
            selectedBidId = this.getAttribute('data-bid-id');
            selectedProjectSlug = this.getAttribute('data-project-slug');

            // Show the modal
            var markWinnerModal = new bootstrap.Modal(document.getElementById('markWinnerModal'));
            markWinnerModal.show();
        });
    });

    // Confirm winner and send AJAX request
    document.querySelector('.confirm-winner-btn').addEventListener('click', function () {
        if (selectedBidId && selectedProjectSlug) {
            // Send AJAX request to mark the bid as winner
            fetch(`/admin/projects/${selectedProjectSlug}/bids/${selectedBidId}/winner`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    var markWinnerModal = bootstrap.Modal.getInstance(document.getElementById('markWinnerModal'));
                    markWinnerModal.hide();

                    // Show success message and reload the page
                    alert('Bid has been successfully marked as the winner.');
                    window.location.reload();
                } else {
                    alert('Failed to mark the bid as winner.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error occurred. Please try again.');
            });
        }
    });
</script>
@endsection --}}


{{-- @extends('layouts.app')

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
                <tr>
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
                            @if ($bid->id == $lowestBid->id)
                                <span class="badge bg-success">Lowest Bid</span>
                            @else
                                <span class="badge bg-secondary">Other Bid</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <!-- Mark as Winner Button -->
                        @if(!$bid->is_winner)
                            <button type="button" class="btn btn-sm btn-primary" onclick="markAsWinner('{{ $project->slug }}', '{{ $bid->id }}')">
                                Mark as Winner
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hidden form for marking the bid as a winner -->
        <form id="markAsWinnerForm" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="_method" value="POST">
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function markAsWinner(projectSlug, bidId) {
        // Create the form action URL
        const form = document.getElementById('markAsWinnerForm');
        form.action = `/admin/projects/${projectSlug}/bids/${bidId}/winner`;

        // Submit the hidden form
        form.submit();
    }
</script>
@endsection --}}

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
                <tr>
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
                        <!-- Mark as Winner Button -->
                        @if(!$bid->is_winner)
                            {{-- <button type="button" class="btn btn-sm btn-primary" onclick="markAsWinner('{{ $project->slug }}', '{{ $bid->id }}')">
                                Mark as Winner
                            </button> --}}
                            <form action="/admin/projects/{{ $project->slug }}/bids/{{ $bid->id }}/winner" method="POST" onsubmit="logFormData('{{ $project->slug }}', '{{ $bid->id }}')">
    @csrf
    <button type="submit" class="btn btn-primary">Mark as Winner</button>
</form>

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hidden form for marking the bid as a winner -->
        {{-- <form id="markAsWinnerForm" method="POST" style="display: none;">
            @csrf
            @method('PATCH') <!-- PATCH method for marking winner -->
        </form> --}}
    @endif
</div>
@endsection

@section('scripts')
<script>
    function markAsWinner(projectSlug, bidId) {
        // Log values to ensure they are being passed correctly
        console.log('Project Slug:', projectSlug);
        console.log('Bid ID:', bidId);
        // Create the form action URL
        const form = document.getElementById('markAsWinnerForm');
        form.action = `/admin/projects/${projectSlug}/bids/${bidId}/winner`;

        // Submit the hidden form
        form.submit();
    }

    function logFormData(projectSlug, bidId) {
        console.log('Project Slug:', projectSlug);
        console.log('Bid ID:', bidId);
    }
</script>
@endsection
