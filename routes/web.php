<?php

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/admin/auth', [AuthController::class, 'googleAuth'])->name('admin.auth');
Route::get('/process/login', [AuthController::class, 'processLogin'])->name('processLogin');


Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
   Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

   Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
   Route::get('/moderation/reports', [ModerationController::class, 'index'])->name('moderation.reports');

   Route::get('/users/index', [UserController::class, 'getAllUsers'])->name('users.index');
   Route::get('/users/{user}/activity', [UserController::class, 'showActivity'])->name('users.activity');
   Route::post('/users/{userId}/block', [UserController::class, 'blockUser'])->name('users.block');
   Route::post('/users/{userId}/unblock', [UserController::class, 'unblockUser'])->name('users.unblock');

   Route::resource('announcements', AnnouncementController::class);
   Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
   Route::get('/role', [RoleController::class, 'index'])->name('platform.roles');
   // dashboard
   Route::get('/dashboard/main', [DashboardController::class, 'showMainDashboard'])->name('dashboard.main');
   Route::get('/dashboard/statistics-data', [DashboardController::class, 'getStatisticsDataProxy'])->name('dashboard.statistics-data');
   Route::get('moderation/dashboard', [DashboardController::class, 'showReportDashboard'])->name('moderation.dashboard');
   Route::get('dashboard/report-data', [DashboardController::class, 'getReportDataProxy'])->name('dashboard.report-data.proxy');
});

// Route::get('/admin/moderation/log', [MainController::class, 'moderationLog'])->name('admin.moderation.log');
// Route::get('/admin/support/index', [MainController::class, 'support'])->name('admin.support.index');
