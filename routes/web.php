<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Admin route to manage users
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
});

// Authenticated user routes (common routes)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard route (common for all authenticated users)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');
});

// Admin-specific routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route for the admin to view bids for a specific project
    Route::get('/admin/projects/{project}/bids', [ProjectController::class, 'showBids'])->name('admin.projects.bids');

    // Mark a bid as the winning bid (admin only)
    Route::post('/admin/projects/{project}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');

    // Admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin route to manage all projects
    Route::get('/admin/projects', [ProjectController::class, 'index'])->name('admin.projects.index');
});

// Producer-specific routes
Route::middleware(['auth', 'role:producer'])->group(function () {
    // Routes for producers to create and manage projects
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

// Freelancer-specific routes
Route::middleware(['auth', 'role:freelancer'])->group(function () {
    // Route to view freelancer's bids
    Route::get('/my-bids', [BidController::class, 'index'])->name('bids.index');
    
    // Route to display the bidding form
    Route::get('/projects/{project}/bid', [BidController::class, 'create'])->name('bids.create');
    
    // Route to submit the bid
    Route::post('/projects/{project}/bid', [BidController::class, 'store'])->name('bids.store');
});

// Public routes
// Default homepage now lists projects (available to everyone)
Route::get('/', [FreelancerController::class, 'index'])->name('projects.index');

// Route for freelancers to view project details (accessible to authenticated users)
Route::get('/projects/{project}', [FreelancerController::class, 'show'])->name('projects.show');

// Route for freelancers to view available projects
Route::get('/projects', [FreelancerController::class, 'index'])->name('projects.index');

// Auth routes
require __DIR__.'/auth.php';
