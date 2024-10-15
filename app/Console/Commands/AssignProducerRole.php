<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AssignProducerRole extends Command
{
    protected $signature = 'assign:producer-role';
    protected $description = 'Assigns the producer role to a user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Create the producer role if it doesn't exist
        Role::firstOrCreate(['name' => 'producer']);

        // Find the user (replace 1 with the actual user ID)
        $user = User::find(1); // Change the ID as needed

        // Assign the producer role
        if ($user) {
            $user->assignRole('producer');
            $this->info('Producer role assigned to user.');
        } else {
            $this->error('User not found.');
        }
    }
}
