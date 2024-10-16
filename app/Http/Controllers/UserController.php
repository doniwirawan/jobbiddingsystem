<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Admin method to list all users
    public function index()
    {
        // Retrieve all users from the database
        $users = User::all();

        // Return a view with the users data
        return view('admin.users.index', compact('users'));
    }
}
