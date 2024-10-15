@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Profile</h2>

    <!-- Display success messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- User Profile Details -->
    <div class="card mb-4">
        <div class="card-header">Profile Information</div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>

                <!-- Show User's Roles -->
                <div class="mb-3">
                    <label for="roles" class="form-label">Roles</label>
                    <ul class="list-group">
                        @foreach ($user->roles as $role)
                            <li class="list-group-item">{{ ucfirst($role->name) }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Optional Password Reset -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (Leave blank if not changing)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
