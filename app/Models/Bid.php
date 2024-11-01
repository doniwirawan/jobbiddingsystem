<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Import the HasRoles trait
use Laravel\Sanctum\HasApiTokens; 

class Bid extends Model
{
     use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = ['user_id', 'project_id', 'amount', 'remarks','   is_winner','created_at','updated_at','is_accepted','accepted_at','deadline'];

    // Each bid belongs to a user (freelancer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each bid belongs to a project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
