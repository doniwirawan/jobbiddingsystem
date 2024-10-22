<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\Project;
use App\Models\Bid;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if the authenticated user has the admin role
        if (!in_array(auth()->user()->role, ['admin', 'producer'])) {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        // Fetch recent projects, bids, and users
        $recentProjects = Project::orderBy('created_at', 'desc')->take(5)->get();
        $recentBids = Bid::with('user', 'project')->orderBy('created_at', 'desc')->take(5)->get();
        $recentUsers = User::with('roles')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('recentProjects', 'recentBids', 'recentUsers'));
    }

    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'producer'])) {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        // Fetch users with their roles and paginate the results
        $users = User::with('roles')->paginate(10);
        
        // Fetch all roles to display in the form for role assignment
        $roles = Role::all();
        
        // Fetch all projects if you need them for some other part of the view
        $projects = Project::all(); 
        
        // Pass users, roles, and projects to the view
        return view('admin.users.index', compact('users', 'roles', 'projects'));
    }


    public function projects()
    {
        if (!in_array(auth()->user()->role, ['admin', 'producer'])) {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $projects = Project::all(); 
        $users = User::all();
        $roles = Role::all(); // Fetch all roles from the database
        return view('admin.projects.index', compact('users', 'roles','projects'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles.*' => 'exists:roles,name',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles to the user
        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    
    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    // Show the form for editing the user
    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $roles = Role::all(); // Get all available roles
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Update user information and roles
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required|array',
        ]);

        // Update user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // 'roles' => $request->roles,
        ]);

        // Sync the roles
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access, redirect to custom 403 page
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
