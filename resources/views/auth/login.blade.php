@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Logo or Branding -->
            <div class="text-center mb-4">
                <h1 class="display-5 fw-bold">Jobs at Studio Five</h1>
                <p class="text-muted">Welcome back! Please log in to your account to continue.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control" name="email" :value="old('email')" required autofocus autocomplete="username">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">{{ __('Remember Me') }}</label>
                        </div>

                        <!-- Actions (Login & Forgot Password) -->
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Forgot Password -->
                            @if (Route::has('password.request'))
                                <a class="text-muted" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary">
                                {{ __('Log In') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center mt-3">
                <p class="text-muted">Don't have an account? 
                    <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-primary">{{ __('Register Here') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
