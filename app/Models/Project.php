<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Project extends Model
{
    use HasFactory;

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

