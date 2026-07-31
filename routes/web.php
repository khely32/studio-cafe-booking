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

Route::get('/', function () {
    $services = \App\Models\Service::active()->get();
    $addons = \App\Models\Addon::active()->orderBy('sort_order')->get();
    $policyContent = \App\Models\Setting::get('home_policy', '');
    $guidesContent = \App\Models\Setting::get('home_guides', '');
    return view('home', compact('services', 'addons', 'policyContent', 'guidesContent'));
})->name('home');

Route::get('/studio', function () {
    return response()->file(public_path('landing/index.html'));
})->name('studio.landing');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/api/payment-reminders', function () {
    $secret = env('VERCEL_CRON_SECRET');
    if ($secret) {
        abort_unless(request()->header('Authorization') === 'Bearer ' . $secret, 403);
    }

    \App\Jobs\SendPaymentReminders::dispatchSync();

    return response('OK');
});

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
    Route::patch('/bookings/{booking}/note', [AdminController::class, 'updateNote'])->name('booking.note');
    Route::delete('/bookings/{booking}', [AdminController::class, 'destroy'])->name('booking.delete');

    Route::resource('pages', PageController::class);
    Route::post('/pages/{page}/duplicate', [PageController::class, 'duplicate'])->name('pages.duplicate');
    Route::patch('/pages/{page}/toggle-publish', [PageController::class, 'togglePublish'])->name('pages.toggle-publish');
    Route::post('/folders', [\App\Http\Controllers\Admin\FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [\App\Http\Controllers\Admin\FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/settings/slack', [AdminController::class, 'saveSlack'])->name('settings.slack');
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('addons', \App\Http\Controllers\Admin\AddonController::class);
    Route::get('/settings/homepage', [\App\Http\Controllers\Admin\SettingsController::class, 'homepage'])->name('settings.homepage');
    Route::post('/settings/homepage', [\App\Http\Controllers\Admin\SettingsController::class, 'updateHomepage'])->name('settings.homepage.update');
    Route::get('/settings/reminders', [\App\Http\Controllers\Admin\SettingsController::class, 'reminders'])->name('settings.reminders');
    Route::post('/settings/reminders', [\App\Http\Controllers\Admin\SettingsController::class, 'updateReminders'])->name('settings.reminders.update');
    Route::post('/settings/reminders/run', [\App\Http\Controllers\Admin\SettingsController::class, 'runReminders'])->name('settings.reminders.run');
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
