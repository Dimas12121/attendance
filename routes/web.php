<?php

use App\Http\Controllers\SettingController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes (Optional: if they want a welcome page)
Route::get('/', function () {
    return view('welcome');
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SettingController::class, 'dashboard'])->name('dashboard');
    
    // Redirect / to /dashboard if logged in is handled by welcome.blade logic or Route::redirect
    
    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    // Student Management (CRUD)
    Route::resource('students', StudentController::class);

    // Attendance / Scanning
    Route::get('/scan', [AttendanceController::class, 'index'])->name('scan');
    Route::post('/scan', [AttendanceController::class, 'store'])->name('scan.store');
    Route::get('/logs', [AttendanceController::class, 'logs'])->name('logs');
    Route::delete('/logs/clear', [AttendanceController::class, 'clearAll'])->name('logs.clear');
    Route::delete('/logs/{attendance}', [AttendanceController::class, 'destroy'])->name('logs.destroy');

    // Profile Management (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook WhatsApp (Fonnte) - Usually public
Route::post('/webhook/whatsapp', [WhatsAppController::class, 'webhook']);

// Hosting Helper (Run via browser once after upload)
Route::get('/storage-link', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return "Storage link created successfully.";
});

require __DIR__.'/auth.php';
