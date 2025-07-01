<?php

use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/auth', [AuthController::class, 'googleAuth'])->name('admin.auth');
Route::get('/process/login', [AuthController::class, 'processLogin'])->name('processLogin');


Route::middleware(['admin.auth'])->name('admin.')->group(function () {
   Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
   Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
   Route::get('/dashboard/main', [DashboardController::class, 'showMainDashboard'])->name('dashboard.main');
   Route::get('/dashboard/statistics-data', [DashboardController::class, 'getStatisticsDataProxy'])->name('dashboard.statistics-data');

   Route::middleware('role:content-manager,super-admin')->group(function () {
      Route::get('moderation/dashboard', [DashboardController::class, 'showReportDashboard'])->name('moderation.dashboard');
      Route::get('dashboard/report-data', [DashboardController::class, 'getReportDataProxy'])->name('dashboard.report-data.proxy');
      Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');

      Route::get('/questions/index', [QuestionController::class, 'index'])->name('questions.index');

   });
   Route::middleware('role:moderator,super-admin')->group(function () {
      Route::resource('announcements', AnnouncementController::class);
   });

   Route::middleware('role:user-manager,super-admin')->group(function () {
      Route::get('/users/index', [UserController::class, 'getAllUsers'])->name('users.index');
      Route::get('/users/{user}/activity', [UserController::class, 'showActivity'])->name('users.activity');
      Route::post('/users/{userId}/block', [UserController::class, 'blockUser'])->name('users.block');
      Route::post('/users/{userId}/unblock', [UserController::class, 'unblockUser'])->name('users.unblock');
   });

   Route::middleware('role:comunity-manager,super-admin')->group(function () {
      Route::get('/moderation/reports', [ModerationController::class, 'index'])->name('moderation.reports');
   });

   Route::middleware('role:super-admin')->group(function () {
      Route::get('/role', [RoleController::class, 'index'])->name('platform.roles');
   });
});
