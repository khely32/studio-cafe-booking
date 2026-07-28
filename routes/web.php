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

Route::get('/', function () {
    $services = \App\Models\Service::active()->get();
    return view('home', compact('services'));
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/service/{service}', [BookingController::class, 'showService'])->name('service');
    Route::get('/slots', [BookingController::class, 'getAvailableSlots'])->name('slots');
    Route::get('/calendar', [BookingController::class, 'getCalendarDates'])->name('calendar');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    Route::get('/confirmation/{bookingRef}', [BookingController::class, 'confirmation'])->name('confirmation');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [AdminController::class, 'bookingDetail'])->name('booking.detail');
    Route::patch('/bookings/{booking}/status', [AdminController::class, 'updateStatus'])->name('booking.update');

    Route::resource('pages', PageController::class);
    Route::resource('team', TeamController::class);
    Route::resource('templates', TemplateController::class);
    Route::resource('polls', PollController::class)->except(['show']);
    Route::post('/polls/{poll}/toggle-close', [PollController::class, 'toggleClose'])->name('polls.toggle-close');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});

Route::get('/poll/{poll}', [\App\Http\Controllers\PollController::class, 'show'])->name('polls.show');
Route::post('/poll/{poll}/vote', [\App\Http\Controllers\PollController::class, 'vote'])->name('polls.vote');
