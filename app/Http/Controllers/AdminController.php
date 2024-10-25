<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Project;
use App\Models\Bid;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;


class AdminController extends Controller
{
    public function dashboard()
    {
        if (!in_array(auth()->user()->role, ['admin', 'producer'])) {
            abort(403);  // Unauthorized access
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
            abort(403);
        }

        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        $projects = Project::all(); 
        
        return view('admin.users.index', compact('users', 'roles', 'projects'));
    }

    public function projects()
    {
        if (!in_array(auth()->user()->role, ['admin', 'producer'])) {
            abort(403);
        }

        $projects = Project::all();
        $users = User::all();
        $roles = Role::all();

        return view('admin.projects.index', compact('users', 'roles', 'projects'));
    }

    public function store(Request $request)
    {
        // Check if the user is an admin before proceeding
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access
        }

        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array', // Required to select at least one role
            'roles.*' => 'exists:roles,name', // Each role must exist in the roles table
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->roles[0],  // Store the first role selected in the 'role' column
        ]);

        // Assign the selected roles to the user
        $user->assignRole($request->roles);

        // Redirect to the users index with a success message
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }


    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Check if the user is an admin before proceeding
        if (auth()->user()->role !== 'admin') {
            abort(403);  // Unauthorized access
        }

        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required|array', // Required to select at least one role
            'roles.*' => 'exists:roles,name', // Each role must exist in the roles table
        ]);

        // Update the user details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->roles[0],  // Update the 'role' column with the first selected role
        ]);

        // Sync the roles to ensure the user has the correct roles assigned
        $user->syncRoles($request->roles);

        // Redirect to the users index with a success message
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
