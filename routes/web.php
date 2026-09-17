<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('events.index');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Regular User Route
    Route::get('/my-dashboard', [EventController::class, 'userDashboard'])->name('user.dashboard');
    Route::post('/my-dashboard/events', [EventController::class, 'store'])->name('events.store');

    // API Route for Dependent Dropdown (accessible to all authenticated users)
    Route::get('/api/marcoms', function (\Illuminate\Http\Request $request) {
        return \App\Models\Marcom::where('brand_id', $request->query('brand_id'))
                                 ->where('branch_id', $request->query('branch_id'))
                                 ->get();
    });

    // Admin Routes
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('/events/analytics', [EventController::class, 'analytics'])->name('events.analytics');
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/regional-calendar', [EventController::class, 'regionalCalendar'])->name('events.regional_calendar');
        
        Route::get('/events/regional', [EventController::class, 'regionalIndex'])->name('events.regional.index');
        Route::post('/events/regional/remove', [EventController::class, 'removeBulkRegional'])->name('events.regional.remove');
        
        Route::patch('/events/{event}/regional', [EventController::class, 'updateRegional'])
            ->name('events.regional');
            
        Route::patch('/events/{event}/result', [EventController::class, 'updateResult'])
            ->name('events.result');
    });
});

use Illuminate\Support\Facades\Artisan;

Route::get('/rahasia-seed', function () {
    Artisan::call('db:seed', ['--force' => true]);
    return 'Alhamdulillah, Seeding dari Render Berhasil!';
});