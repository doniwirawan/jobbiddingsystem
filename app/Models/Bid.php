<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Bid extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'user_id',
        'project_id',
        'amount',
        'remarks',
        'is_winner',
        'is_accepted',
        'accepted_at',
        'deadline',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_winner' => 'boolean',
        'is_accepted' => 'boolean',
        'accepted_at' => 'datetime',
        'deadline' => 'datetime',
    ];

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
