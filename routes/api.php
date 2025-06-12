<?php

use App\Http\Controllers\Api\RoomAvailabilityController;
use App\Http\Controllers\Api\ConflictCheckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Room availability checking routes - accessible to all users
Route::post('/check-room-availability', [RoomAvailabilityController::class, 'checkAvailability'])
    ->name('api.room.check-availability');
Route::get('/room-schedule', [RoomAvailabilityController::class, 'getRoomSchedule'])
    ->name('api.room.schedule');

// Live conflict checking routes
Route::post('/check-conflicts', [ConflictCheckController::class, 'checkConflicts'])
    ->name('api.conflicts.check');
Route::get('/room-schedule-detailed', [ConflictCheckController::class, 'getRoomSchedule'])
    ->name('api.room.schedule-detailed');
Route::post('/available-slots', [ConflictCheckController::class, 'getAvailableSlots'])
    ->name('api.room.available-slots');
