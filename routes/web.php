<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;  // Import the HasRoles trait
use Laravel\Sanctum\HasApiTokens; 
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('role:admin')->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Admin routes (only for admin role)
Route::middleware(['auth'])->group(function () {
    // Admin user management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');

    // Admin project and bid management
    Route::get('/admin/projects', [AdminController::class, 'projects'])->name('admin.projects.index');
    Route::get('/admin/bids', [BidController::class, 'index'])->name('admin.bids.index');
    Route::get('/admin/projects/{project}/bids', [ProjectController::class, 'showBids'])->name('admin.projects.bids');
    Route::post('/admin/projects/{project}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('admin.projects.markWinner');

    // Admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Producer routes (only for producers)
Route::middleware(['auth'])->group(function () {
    // Routes for producers to create and manage projects
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::patch('/projects/{project}/close', [ProjectController::class, 'close'])->name('projects.close');
    Route::patch('/projects/{project}/open', [ProjectController::class, 'open'])->name('projects.open');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.delete');
});

// Freelancer routes (only for freelancers)
Route::middleware(['auth'])->group(function () {
    // Routes for freelancers to view and bid on projects
    Route::get('/my-bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/projects/{project}/bid', [BidController::class, 'create'])->name('bids.create');
    Route::post('/projects/{project}/bid', [BidController::class, 'store'])->name('bids.store');
});

// Authenticated user routes (common routes for all authenticated users)
Route::middleware('auth')->group(function () {
    // User profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard route (accessible to all authenticated users)
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->middleware('verified')->name('dashboard');
});

// Public routes (accessible to everyone, including unauthenticated users)
Route::get('/', [FreelancerController::class, 'index'])->name('projects.index');
Route::get('/projects', [FreelancerController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [FreelancerController::class, 'show'])->name('projects.show');

// Auth routes
require __DIR__.'/auth.php';
