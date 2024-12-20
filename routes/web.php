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
use Illuminate\Support\Facades\Mail;
use App\Models\Bid;
use App\Models\User;
use App\Notifications\{BidAccepted, BidWonNotification};
// use Illuminate\Bus\Queueable;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;


Route::get('/test-email', function () {
    $email = 'doniwirawan166@gmail.com'; // Target email for testing

    // Send the email with "test" as the content
    Mail::raw('test', function ($message) use ($email) {
        $message->to($email)
                ->subject('Test Email');
    });

    return 'Test email sent to doniwirawan166@gmail.com!';
});

Route::get('/test-bid-won-email', function () {
    // Replace with an actual user ID and bid ID from your database
    $userId = 1; // Example user ID (the freelancer receiving the email)
    $bidId = 21;  // Example bid ID (the bid that won)

    // Find the user and bid
    $user = User::find($userId);
    $bid = Bid::find($bidId);

    if ($user && $bid) {
        // Send the notification
        Notification::send($user, new BidWonNotification($bid));

        return 'Test bid won email sent!';
    }

    return 'User or Bid not found. Please check the user ID and bid ID.';
});



// Public routes accessible to everyone
Route::get('/', [FreelancerController::class, 'index'])->name('projects.index');
Route::get('/projects', [FreelancerController::class, 'index'])->name('projects.index');

// Project creation and management routes for Producers (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');  
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

// Project-specific routes using slugs (authenticated users only)
Route::middleware(['auth'])->group(function () {
    Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project:slug}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project:slug}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project:slug}', [ProjectController::class, 'destroy'])->name('projects.delete');
    Route::patch('/projects/{project:slug}/close', [ProjectController::class, 'close'])->name('projects.close');
    Route::patch('/projects/{project:slug}/open', [ProjectController::class, 'open'])->name('projects.open');
});

// Admin routes for user, project, and bid management
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // Dashboard route
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User management
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
    });

    // Project management with nested bid routes
    Route::prefix('projects')->name('admin.projects.')->group(function () {
        Route::get('/', [AdminController::class, 'projects'])->name('index');
        Route::get('/{project}/bids', [ProjectController::class, 'showBids'])->name('bids');
        
        // Routes for marking a winner and sending email
        Route::post('/{project:slug}/bids/{bid}/winner', [ProjectController::class, 'markWinner'])->name('markWinner');
        Route::post('/{project:slug}/bids/{bid}/email-winner', [ProjectController::class, 'sendEmailToWinner'])->name('sendEmailToWinner');

        // Routes for closing, opening, and deleting a project
        Route::patch('/{project:slug}/close', [ProjectController::class, 'close'])->name('close');
        Route::patch('/{project:slug}/open', [ProjectController::class, 'open'])->name('open');
        Route::delete('/{project:slug}', [ProjectController::class, 'destroy'])->name('destroy');
    });

    // Bid management
    Route::prefix('bids')->name('admin.bids.')->group(function () {
        Route::get('/', [BidController::class, 'allBids'])->name('index');
        Route::patch('/{bid}/close', [BidController::class, 'close'])->name('close');
        Route::delete('/{bid}', [BidController::class, 'destroy'])->name('destroy');
    });
});

// Freelancer routes for bid-related actions
Route::middleware(['auth'])->group(function () {
    Route::get('/my-bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('/projects/{project:slug}/bid', [BidController::class, 'create'])->name('bids.create');
    Route::post('/projects/{project:slug}/bid', [BidController::class, 'store'])->name('bids.store');
    Route::get('/projects/{project:slug}/bids/history', [BidController::class, 'history'])->name('bids.history');

    Route::patch('/bids/{bid}/accept', [BidController::class, 'accept'])->name('bids.accept');
    Route::patch('/bids/{bid}/reject', [BidController::class, 'reject'])->name('bids.reject');

    // Experimental routes using GET requests
    Route::get('/bids/{bid}/accept', [BidController::class, 'acceptBid'])->name('bids.accept');
    Route::get('/bids/{bid}/reject', [BidController::class, 'rejectBid'])->name('bids.reject');

    Route::delete('/projects/{project:slug}/bids/{bid}', [BidController::class, 'destroy'])->name('bids.destroy');

});

// Authenticated user profile and dashboard routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Authentication routes
require __DIR__.'/auth.php';
