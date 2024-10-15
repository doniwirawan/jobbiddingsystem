<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AssignProducerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles if they don't exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'producer']);
        Role::firstOrCreate(['name' => 'freelancer']);

        // Example: Assign the producer role to a specific user by ID
        $producerEmail = 'doni@studiofivecorp.com'; // Replace with an actual email

        // Find the user by email (you can also use another field, e.g., ID)
        $producerUser = User::where('email', $producerEmail)->first();

        if ($producerUser) {
            // Assign the 'producer' role to the user
            if (!$producerUser->hasRole('producer')) {
                $producerUser->assignRole('producer');
                echo "Producer role assigned to user with email: {$producerEmail}.\n";
            } else {
                echo "User already has the producer role.\n";
            }
        } else {
            echo "User with email {$producerEmail} not found.\n";
        }

        // Example: Assign admin role to another user if needed
        $adminEmail = 'doni@studiofivecorp.com'; // Replace with an actual admin email
        $adminUser = User::where('email', $adminEmail)->first();

        if ($adminUser) {
            if (!$adminUser->hasRole('admin')) {
                $adminUser->assignRole('admin');
                echo "Admin role assigned to user with email: {$adminEmail}.\n";
            } else {
                echo "User already has the admin role.\n";
            }
        } else {
            echo "User with email {$adminEmail} not found.\n";
        }
    }
}
