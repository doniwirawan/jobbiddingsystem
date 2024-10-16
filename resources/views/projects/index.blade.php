@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Available Projects</h2>

    @if($projects->isEmpty())
        <p>No projects available at the moment.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Entity</th>
                    <th>Type</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a></td>
                    <td>{{ $project->date }}</td>
                    <td>{{ $project->entity }}</td>
                    <td>{{ $project->type }}</td>
                    <td>{{ $project->rate }}</td>
                    <td>{{ $project->status }}</td>
                    <td>
                        <!-- Action Buttons -->
                        <form action="{{ route('projects.delete', $project->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                        @if($project->status === 'Open')
                            <form action="{{ route('projects.close', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm">Close</button>
                            </form>
                        @else
                            <form action="{{ route('projects.open', $project->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Open</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
