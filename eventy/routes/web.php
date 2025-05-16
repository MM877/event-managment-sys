<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SavedEventController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TicketController;

Route::redirect('/', '/events');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Events routes
Route::resource('events', EventController::class);

// Route to show events created by the authenticated user
Route::get('/my-events', [EventController::class, 'myEvents'])
    ->middleware('auth')
    ->name('my-events.index'); // Changed from events.index to my-events.index

// Remove this duplicate route as it's already handled by the resource controller
// Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Likes routes
Route::post('/events/{event}/like', [LikeController::class, 'store'])->name('events.like');
Route::delete('/events/{event}/unlike', [LikeController::class, 'destroy'])->name('events.unlike');

// Comments routes
Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

// Attendance routes
Route::resource('attending', AttendingController::class)->middleware('auth');
// API routes for form dependencies
Route::get('countries/{country}/cities', [CountryController::class, 'getCities'])->name('countries.cities');

// Notifications routes
Route::post('/notifications/mark-as-read', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        \Illuminate\Support\Facades\Auth::user()->unreadNotifications->markAsRead();
    }
    return response()->json(['success' => true]);
})->name('notifications.markAsRead');

// Ticket routes
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::patch('/tickets/{ticket}/cancel', [TicketController::class, 'cancel'])->name('tickets.cancel');
});

// Other resource routes
Route::resource('comments', CommentController::class);
Route::resource('likes', LikeController::class);
Route::resource('saved-events', SavedEventController::class)->middleware('auth');
Route::resource('tags', TagController::class);
Route::resource('galleries', GalleryController::class);

require __DIR__.'/auth.php';

Route::get('/events/{event}/gallery', [GalleryController::class, 'index'])->name('events.gallery.index');
Route::post('/events/{event}/gallery', [GalleryController::class, 'store'])->name('events.gallery.store');



