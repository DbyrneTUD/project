<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LiftRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Guest Route for welcome page
Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

// Github login/register routes
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::updateOrCreate([
        'github_id' => $githubUser->id,
    ], [
        'name' => $githubUser->name,
        'email' => $githubUser->email,
        'password' => Str::random(40),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

//Main auth routes for web app
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::post('groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    Route::get('/groups/{group}/requests/create', [LiftRequestController::class, 'create'])->name('requests.create');
    Route::post('/groups/{group}/requests', [LiftRequestController::class, 'store'])->name('requests.store');
    Route::get('/groups/{group}/requests/{liftRequest}', [LiftRequestController::class, 'show'])->name('requests.show');
    Route::get('/groups/{group}/requests/{liftRequest}/edit', [LiftRequestController::class, 'edit'])->name('requests.edit');
    Route::patch('/groups/{group}/requests/{liftRequest}', [LiftRequestController::class, 'update'])->name('requests.update');
    Route::delete('/groups/{group}/requests/{liftRequest}', [LiftRequestController::class, 'destroy'])->name('requests.destroy');
    Route::post('groups/{group}/requests/{liftRequest}/accept', [LiftRequestController::class, 'accept'])->name('requests.accept');
    Route::post('/groups/{group}/requests/{liftRequest}/cancel', [LiftRequestController::class, 'cancel'])->name('requests.cancel');
    Route::post('/groups/{group}/requests/{liftRequest}/complete', [LiftRequestController::class, 'complete'])->name('requests.complete');

    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/trips/{trip}/messages', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/trips/{trip}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/trips/{trip}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/trips/{trip}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/trips/{trip}/review/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
});

require __DIR__.'/auth.php';
