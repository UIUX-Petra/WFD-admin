<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/admin/auth', [AuthController::class, 'googleAuth'])->name('admin.auth');
Route::get('/process/login', [AuthController::class, 'processLogin'])->name('processLogin');

// Menangani logout admin
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {
   Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
   Route::get('/moderation/reports', [ModerationController::class, 'index'])->name('moderation.reports');
   Route::get('/users/index', [UserController::class, 'getAllUsers'])->name('users.index');
   Route::post('/users/{userId}/block', [UserController::class, 'blockUser'])->name('users.block');
   Route::post('/users/{userId}/unblock', [UserController::class, 'unblockUser'])->name('users.unblock');
});

// Route::get('/admin/users/index', [MainController::class, 'userRegistration'])->name('admin.users.index');
Route::get('/admin/users/activity', [MainController::class, 'userActivity'])->name('admin.users.activity');
Route::get('/admin/moderation/dashboard', [MainController::class, 'moderationDashboard'])->name('admin.moderation.dashboard');
Route::get('/admin/moderation/questions', [MainController::class, 'moderationQuestions'])->name('admin.moderation.questions');
Route::get('/admin/moderation/comments', [MainController::class, 'moderationComments'])->name('admin.moderation.comments');
Route::get('/admin/manage/content', [MainController::class, 'manageContent'])->name('admin.content.manage');
Route::get('/admin/subjects/index', [MainController::class, 'subjects'])->name('admin.subjects.index');
Route::get('/admin/moderation/log', [MainController::class, 'moderationLog'])->name('admin.moderation.log');
Route::get('/admin/support/index', [MainController::class, 'support'])->name('admin.support.index');
Route::get('/admin/platform/annoucement', [MainController::class, 'announcement'])->name('admin.platform.announcements');
Route::get('/admin/platform/role', [MainController::class, 'role'])->name('admin.platform.roles');
