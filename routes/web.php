<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\PollController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\ClientDashboardController;

Route::get('/diag', function () {
    $out = [];
    $default = config('database.default');
    $out['default'] = $default;
    $out['ext_pgsql'] = extension_loaded('pdo_pgsql');
    $out['ext_mysql'] = extension_loaded('pdo_mysql');
    $out['ext_sqlite'] = extension_loaded('pdo_sqlite');
    $out['host'] = config('database.connections.'.$default.'.host');
    $out['port'] = config('database.connections.'.$default.'.port');
    $out['database'] = config('database.connections.'.$default.'.database');
    $out['user'] = config('database.connections.'.$default.'.username');
    $out['sslmode'] = config('database.connections.'.$default.'.sslmode');
    $out['env_PGHOST'] = getenv('PGHOST') ?: 'NOT SET';
    $out['env_PGDATABASE'] = getenv('PGDATABASE') ?: 'NOT SET';
    $out['env_PGUSER'] = getenv('PGUSER') ?: 'NOT SET';
    $out['env_DATABASE_URL'] = getenv('DATABASE_URL') ? 'SET' : 'NOT SET';
    $out['env_DB_CONNECTION'] = getenv('DB_CONNECTION') ?: 'NOT SET';
    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        $out['pdo'] = 'CONNECTED';
    } catch (\Throwable $e) {
        $out['pdo_error'] = get_class($e).': '.$e->getMessage();
    }
    $out['migrations_table'] = \Illuminate\Support\Facades\Schema::hasTable('migrations');
    return response()->json($out);
});

Route::get('/', function () {
    $services = \App\Models\Service::active()->get();
    return view('home', compact('services'));
})->name('home');

Route::get('/studio', function () {
    return response()->file(public_path('landing/index.html'));
})->name('studio.landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.send');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/service/{service}', [BookingController::class, 'showService'])->name('service');
    Route::get('/slots', [BookingController::class, 'getAvailableSlots'])->name('slots');
    Route::get('/calendar', [BookingController::class, 'getCalendarDates'])->name('calendar');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{bookingRef}', [BookingController::class, 'confirmation'])->name('confirmation');
});

Route::get('/page/{slug}', [\App\Http\Controllers\Admin\PageController::class, 'showBySlug'])->name('pages.public');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'bookingDetail'])->name('booking.detail');
    Route::patch('/bookings/{booking}/status', [AdminController::class, 'updateStatus'])->name('booking.update');

    Route::resource('pages', PageController::class);
    Route::post('/pages/{page}/duplicate', [PageController::class, 'duplicate'])->name('pages.duplicate');
    Route::patch('/pages/{page}/toggle-publish', [PageController::class, 'togglePublish'])->name('pages.toggle-publish');
    Route::post('/folders', [\App\Http\Controllers\Admin\FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [\App\Http\Controllers\Admin\FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/settings/slack', [AdminController::class, 'saveSlack'])->name('settings.slack');
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('team', TeamController::class);
    Route::resource('templates', TemplateController::class);
    Route::resource('polls', PollController::class)->except(['show']);
    Route::post('/polls/{poll}/toggle-close', [PollController::class, 'toggleClose'])->name('polls.toggle-close');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

Route::prefix('dashboard')->name('client.')->middleware('client')->group(function () {
    Route::get('/', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/booking/{booking}', [ClientDashboardController::class, 'bookingDetail'])->name('booking.detail');
});

Route::get('/poll/{poll}', [\App\Http\Controllers\PollController::class, 'show'])->name('polls.show');
Route::post('/poll/{poll}/vote', [\App\Http\Controllers\PollController::class, 'vote'])->name('polls.vote');
