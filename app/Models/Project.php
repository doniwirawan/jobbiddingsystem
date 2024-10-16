<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Import the HasRoles trait
use Laravel\Sanctum\HasApiTokens; 

class Project extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'date',
        'entity',
        'type',
        'rate',
        'role',
        'remarks',
        'status'
    ];
}

