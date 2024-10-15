<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class RoleController extends Controller
{
    public function assignProducerRole()
    {
        // Create the producer role if it doesn't exist
        Role::firstOrCreate(['name' => 'producer']);

        // Find the user by ID (replace 1 with the user's actual ID)
        $user = User::find(1); // Replace with the actual user ID

        // Assign the producer role to the user
        if ($user) {
            $user->assignRole('producer');
            return "Producer role assigned to user.";
        } else {
            return "User not found.";
        }
    }
}
