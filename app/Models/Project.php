<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Import the HasRoles trait
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Support\Str;

class Project extends Model
{

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'date',
        'start_date',     // Add start_date
        'end_date', 
        'entity',
        'type',
        'rate',
        'role',
        'remarks',
        'status',
        'created_by',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Use the 'slug' for route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
    // Automatically generate slug when saving a new project
    protected static function boot()
    {
        parent::boot();

        // Listen for the 'creating' event to generate slug
        static::creating(function ($project) {
            $slug = Str::slug($project->name);
            $count = Project::where('slug', 'LIKE', "{$slug}%")->count();

            $project->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        // Listen for the 'updating' event to generate slug if the name changes
        static::updating(function ($project) {
            if ($project->isDirty('name')) {
                $project->slug = Str::slug($project->name);
            }
        });
    }
}

