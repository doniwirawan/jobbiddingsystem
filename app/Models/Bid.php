<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'project_id', 'amount', 'remarks'];

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
