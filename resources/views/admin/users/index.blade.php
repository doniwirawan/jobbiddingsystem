@extends('layouts.app')

@section('content')
<div class="container mt-4 ">
    <h2 class="fw-bold mb-4"><i class="bi bi-people-fill"></i> Manage Users</h2>
{{-- 
    <!-- Button to trigger Add User Form (inline) -->
    <div class="d-flex justify-content-between mb-4">
        <button class="btn btn-primary" onclick="document.getElementById('addUserForm').style.display = (document.getElementById('addUserForm').style.display === 'none') ? 'block' : 'none';">
            <i class="bi bi-person-plus-fill"></i> Add New User
        </button>
    </div>

  
   <!-- Add New User Form -->
    <div id="addUserForm" class="card shadow-sm p-3 mb-4" style="display: none;">
        <h5 class="card-title"><i class="bi bi-person-fill"></i> New User Details</h5>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <!-- Name Input -->
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Input -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Input -->
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role Selection Input -->
            <div class="mb-3">
                <label for="roles" class="form-label">Assign Roles:</label>
                <select name="roles[]" class="form-select" multiple required>
                    @foreach(Auth::user()->roles as $role) <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option> @endforeach
                </select>
                @error('roles')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Create User</button>
        </form>
    </div> --}}


    <!-- User List Table -->
    @if($users->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle-fill"></i> No users found.
        </div>
    @else
        <table class="table table-hover shadow-sm">
            <thead class="table-light">
                <tr>
                    <th><i class="bi bi-person-fill"></i> Name</th>
                    <th><i class="bi bi-envelope-fill"></i> Email</th>
                    <th><i class="bi bi-award-fill"></i> Role(s)</th>
                    <th><i class="bi bi-gear-fill"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash-fill"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        {{-- <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div> --}}
    @endif
</div>
@endsection
