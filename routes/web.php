<?php

use App\Http\Controllers\PMIController;
use App\Http\Controllers\RSController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Home redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// PMI Routes
Route::middleware(['auth', \App\Http\Middleware\CheckUserType::class . ':pmi'])->prefix('pmi')->group(function () {
    Route::get('/dashboard', [PMIController::class, 'dashboard'])->name('pmi.dashboard');
    
    // Blood Stock Management
    Route::get('/blood-stock', [PMIController::class, 'bloodStock'])->name('pmi.blood-stock');
    Route::post('/blood-stock', [PMIController::class, 'updateBloodStock']);
    Route::post('/blood-stock/adjust', [PMIController::class, 'adjustStock'])->name('pmi.blood-stock.adjust');
    Route::delete('/blood-stock/{id}', [PMIController::class, 'deleteBloodStock']);
    
    // Blood Requests
    Route::get('/blood-requests', [PMIController::class, 'bloodRequests'])->name('pmi.blood-requests');
    Route::post('/blood-requests/{id}/verify', [PMIController::class, 'verifyBloodRequest']);
    
    // Distribution
    Route::get('/distribution', [PMIController::class, 'distribution'])->name('pmi.distribution');
    Route::post('/distribution', [PMIController::class, 'createDistribution']);
    // Update distribution status
    Route::post('/distribution/{id}/update-status', [PMIController::class, 'updateDistributionStatus'])->name('pmi.distribution.update_status');
    
    // Hospitals
    Route::get('/hospitals', [PMIController::class, 'hospitals'])->name('pmi.hospitals');
    // Create new hospital
    Route::post('/hospitals', [PMIController::class, 'storeHospital'])->name('pmi.hospitals.store');
    // Show single hospital
    Route::get('/hospitals/{id}', [PMIController::class, 'showHospital'])->name('pmi.hospitals.show');
    // Update hospital
    Route::put('/hospitals/{id}', [PMIController::class, 'updateHospital'])->name('pmi.hospitals.update');
    // Toggle hospital status
    Route::put('/hospitals/{id}/status', [PMIController::class, 'toggleHospitalStatus'])->name('pmi.hospitals.toggle_status');
    
    // Settings
    Route::get('/settings', [PMIController::class, 'settings'])->name('pmi.settings');
    Route::post('/settings/users', [PMIController::class, 'createUser'])->name('pmi.settings.users.create');
    Route::put('/settings/users/{id}', [PMIController::class, 'updateUser'])->name('pmi.settings.users.update');
    Route::delete('/settings/users/{id}', [PMIController::class, 'deleteUser'])->name('pmi.settings.users.delete');
});

// Rumah Sakit Routes
Route::middleware(['auth', \App\Http\Middleware\CheckUserType::class . ':rumah_sakit'])->prefix('rs')->group(function () {
    Route::get('/dashboard', [RSController::class, 'dashboard'])->name('rs.dashboard');
    
    // Blood Requests
    Route::get('/create-request', [RSController::class, 'createRequest'])->name('rs.create-request');
    Route::post('/create-request', [RSController::class, 'storeRequest']);
    Route::get('/requests', [RSController::class, 'requests'])->name('rs.requests');
    
    // Blood Receipt
    Route::get('/blood-receipt', [RSController::class, 'bloodReceipt'])->name('rs.blood-receipt');
    Route::post('/blood-receipt/{id}/confirm', [RSController::class, 'confirmReceipt']);
    
    // History
    Route::get('/history', [RSController::class, 'history'])->name('rs.history');
    
    // Profile
    Route::get('/profile', [RSController::class, 'profile'])->name('rs.profile');
    Route::post('/profile', [RSController::class, 'updateProfile']);
    // Staff management for Rumah Sakit
    Route::post('/profile/staff', [RSController::class, 'addStaff'])->name('rs.profile.staff.add');
    Route::delete('/profile/staff/{id}', [RSController::class, 'deleteStaff'])->name('rs.profile.staff.delete');
});

// Simple dashboard redirect setelah login
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->user_type === 'pmi') {
        return redirect()->route('pmi.dashboard');
    } elseif ($user->user_type === 'rumah_sakit') {
        return redirect()->route('rs.dashboard');
    }
    return redirect()->route('login');
})->middleware('auth');