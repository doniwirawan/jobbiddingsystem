@extends('layouts.app')

@section('title', 'All Bids')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold"><i class="bi bi-list"></i> All User Bids</h2>

    @if($bids->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No bids have been placed by users yet.
        </div>
    @else
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Bid ID</th>
                    <th>Project</th>
                    <th>Bidder</th>
                    <th>Bid Amount</th>
                    <th>Status</th>
                    <th>Is Lowest?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bids as $bid)
                <tr>
                    <td>{{ $bid->id }}</td>
                    <td>{{ $bid->project->name }}</td>
                    <td>{{ $bid->user->name }}</td>
                    <td>${{ number_format($bid->amount, 2) }}</td>
                    <td>{{ $bid->status ?? 'Pending' }}</td>
                    <td>
                        @if(isset($lowestBids[$bid->project_id]) && $bid->amount == $lowestBids[$bid->project_id])
                            <span class="badge bg-success">Lowest Bid</span>
                        @else
                            <span class="badge bg-secondary">Not Lowest</span>
                        @endif
                    </td>
                    <td>
                        <!-- Mark as Winner Button -->
                        @if($bid->status !== 'Won')
                            <form action="/admin/projects/{{ $bid->project->slug }}/bids/{{ $bid->id }}/winner" method="POST" onsubmit="logFormData('{{ $bid->project->slug }}', '{{ $bid->id }}')">
                                @csrf
                                <button type="submit" class="btn btn-primary">Mark as Winner</button>
                            </form>
                        @else
                            <span class="badge bg-success">Winner</span>
                        @endif

                        <!-- Optional Close Bid and Delete Buttons -->
                        @if($bid->status !== 'Closed')
                            <form action="{{ route('admin.bids.close', $bid->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to close this bid?')">
                                    <i class="bi bi-x-circle"></i> Close
                                </button>
                            </form>
                        @else
                            <span class="badge bg-danger">Closed</span>
                        @endif

                        <!-- Trigger Delete Modal -->
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bid-id="{{ $bid->id }}" data-bid-project="{{ $bid->project->name }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this bid for <strong><span id="modalProjectName"></span></strong>?
            </div>
            <div class="modal-footer">
                <form id="deleteBidForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Winner Confirmation Modal -->
<div class="modal fade" id="markWinnerModal" tabindex="-1" aria-labelledby="markWinnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markWinnerModalLabel">Confirm Mark as Winner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to set this bid for <strong><span id="modalMarkWinnerProjectName"></span></strong> as the winning bid?
            </div>
            <div class="modal-footer">
                <form id="markWinnerForm" method="POST" action="">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-success">Yes, Set as Winner</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Show modal and update content dynamically
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var bidId = button.getAttribute('data-bid-id');
        var projectName = button.getAttribute('data-bid-project');

        // Update modal content
        var modalProjectName = deleteModal.querySelector('#modalProjectName');
        modalProjectName.textContent = projectName;

        // Update form action URL
        var form = deleteModal.querySelector('#deleteBidForm');
        form.action = '/admin/bids/' + bidId;
    });

    // Show Mark as Winner Modal and set dynamic form action
    var markWinnerModal = document.getElementById('markWinnerModal');
    markWinnerModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var bidId = button.getAttribute('data-bid-id');
        var projectName = button.getAttribute('data-bid-project');

        // Update modal content
        var modalProjectName = markWinnerModal.querySelector('#modalMarkWinnerProjectName');
        modalProjectName.textContent = projectName;

        // Update form action URL
        var form = markWinnerModal.querySelector('#markWinnerForm');
        form.action = '/admin/projects/' + projectName + '/bids/' + bidId + '/winner';
    });

    // Optional: Debug logging function
    function logFormData(projectSlug, bidId) {
        console.log('Project Slug:', projectSlug);
        console.log('Bid ID:', bidId);
    }
</script>
@endsection
