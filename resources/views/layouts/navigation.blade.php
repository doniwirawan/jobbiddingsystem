<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            {{ config('app.name', 'Bidding System') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <!-- Freelancer-specific links -->
                    @role('freelancer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bids.index') ? 'active' : '' }}" href="{{ route('bids.index') }}">
                                <i class="bi bi-list-check"></i> My Bids
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                <i class="bi bi-folder"></i> Available Projects
                            </a>
                        </li>
                    @endrole

                    <!-- Producer-specific links -->
                    @role('producer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}" href="{{ route('projects.create') }}">
                                <i class="bi bi-plus-circle"></i> Create Project
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.projects.index') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                                <i class="bi bi-folder2-open"></i> Manage Projects
                            </a>
                        </li>
                    @endrole

                    <!-- Admin-specific links -->
                    @role('admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer"></i> Admin Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.projects.index') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
                                <i class="bi bi-gear"></i> Manage All Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people"></i> Manage Users
                            </a>
                        </li>
                    @endrole
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                <i class="bi bi-pencil-square"></i> {{ __('Register') }}
                            </a>
                        </li>
                    @endif
                @else
                    <!-- Authenticated User Dropdown -->
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <!-- Display User Roles -->
                            <div class="dropdown-item-text">
                                <strong>Roles:</strong>
                                <ul class="list-unstyled">
                                    @foreach(Auth::user()->roles as $role)
                                        <li>{{ ucfirst($role->name) }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- My Bids (for freelancers) -->
                            @role('freelancer')
                                <a class="dropdown-item {{ request()->routeIs('bids.index') ? 'active' : '' }}" href="{{ route('bids.index') }}">
                                    <i class="bi bi-list-check"></i> {{ __('My Bids') }}
                                </a>
                            @endrole

                            <a class="dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person"></i> {{ __('Profile') }}
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
