@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h1>testing bos</h1> --}}
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
                            <button type="button" class="btn btn-sm btn-primary mark-winner-btn"
                                    data-bid-id="{{ $bid->id }}" data-project-slug="{{ $project->slug }}">
                                Mark as Winner
                            </button>
                        @endif

                        <!-- Button to email the freelancer if they won -->
                        @if($bid->is_winner)
                            <form action="{{ route('admin.projects.sendEmailToWinner', ['project' => $project->slug, 'bid' => $bid->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-envelope-fill"></i> Email Winner
                                </button>
                            </form>
                        @else
                            <a href="mailto:{{ $bid->user->email }}?subject=Bid Status for Project: {{ $project->name }}" class="btn btn-sm btn-info">
                                Email Freelancer
                            </a>
                        @endif
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
                Are you sure you want to mark this bid as the winner?
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
    let selectedBidId = null;
    let selectedProjectSlug = null;

    document.querySelectorAll('.mark-winner-btn').forEach(button => {
        button.addEventListener('click', function () {
            selectedBidId = this.getAttribute('data-bid-id');
            selectedProjectSlug = this.getAttribute('data-project-slug');
            const form = document.getElementById('markWinnerForm');
            form.action = `/admin/projects/${selectedProjectSlug}/bids/${selectedBidId}/winner`;
            new bootstrap.Modal(document.getElementById('markWinnerModal')).show();
        });
    });
</script>
@endsection
