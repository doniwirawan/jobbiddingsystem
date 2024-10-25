<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;



// Public routes (accessible to everyone, including unauthenticated users)
Route::get('/', [FreelancerController::class, 'index'])->name('projects.index');
Route::get('/projects', [FreelancerController::class, 'index'])->name('projects.index');

// Producer routes (must come before slug routes to avoid conflict)
Route::middleware(['auth'])->group(function () {
    // Project creation and management
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');  // Create should be before slug
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

// Slug-based routes for projects (after fixed routes)
Route::middleware(['auth'])->group(function () {
    Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project:slug}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project:slug}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])->name('projects.delete');
    Route::patch('/projects/{project:slug}/close', [ProjectController::class, 'close'])->name('projects.close');
    Route::patch('/projects/{project:slug}/open', [ProjectController::class, 'open'])->name('projects.open');
});

// Admin routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // User management
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Project and bid management

    

    Route::get('/admin/projects', [AdminController::class, 'projects'])->name('admin.projects.index');
    Route::get('/admin/projects/{project}/bids/', [ProjectController::class, 'showBids'])->name('admin.projects.bids');
        // Mark as winner route
    // Route::post('/admin/projects/{project:slug}/bids/{bid}/winner/', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');
    // Route::post('/admin/projects/{project:slug}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');
    // Use PATCH instead of POST for marking a winner
    Route::post('/admin/projects/{project:slug}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');


    Route::post('/admin/projects/{project:slug}/bids/{bid}/email-winner', [ProjectController::class, 'sendEmailToWinner'])->name('admin.projects.sendEmailToWinner');

    // Use PATCH instead of POST for marking a winner
    // Route::patch('/admin/projects/{project:slug}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');
    // Route::get('/admin/projects/{project:slug}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');


    
    // Email the winner route
    Route::post('/admin/projects/{project:slug}/bids/{bid}/email-winner', [ProjectController::class, 'sendEmailToWinner'])->name('admin.projects.sendEmailToWinner');
    
    // Bid management
    Route::get('/admin/bids', [BidController::class, 'allBids'])->name('admin.bids.index');
    Route::patch('/admin/bids/{bid}/close', [BidController::class, 'close'])->name('admin.bids.close');
    Route::delete('/admin/bids/{bid}', [BidController::class, 'destroy'])->name('admin.bids.destroy');
});

// Freelancer routes (only for freelancers)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/projects/{project:slug}/bid', [BidController::class, 'create'])->name('bids.create');
    Route::post('/projects/{project:slug}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::get('/projects/{project:slug}/bids/history', [BidController::class, 'history'])->name('bids.history');
        // Route to accept a bid
    Route::patch('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');

    // Route to reject a bid
    Route::patch('/bids/{bid}/reject', [BidController::class, 'reject'])->name('bids.reject');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Auth routes
require __DIR__.'/auth.php';
