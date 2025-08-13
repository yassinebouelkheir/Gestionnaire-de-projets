<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ImprovementController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\TeamDashboardController;
use App\Http\Controllers\NotificationController;

Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('/projets/{projet}/edit', [ProjetController::class, 'edit'])->name('projets.edit');
        Route::put('/projets/{projet}', [ProjetController::class, 'update'])->name('projets.update');
        Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->name('projets.destroy');

        Route::get('/team-dashboard', [TeamDashboardController::class, 'index'])->name('team.dashboard');
        Route::get('/team/dashboard/export/global', [TeamDashboardController::class, 'exportGlobalCsv'])->name('team.dashboard.export.global');
        Route::get('/team/dashboard/export/devs', [TeamDashboardController::class, 'exportDevsCsv'])->name('team.dashboard.export.devs');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/issues/{id}/edit', [IssueController::class, 'edit'])->name('issues.edit');
    Route::put('/issues/{id}', [IssueController::class, 'update'])->name('issues.update');
    Route::delete('/issues/{id}', [IssueController::class, 'destroy'])->name('issues.destroy');

    Route::get('/improvements/{id}/edit', [ImprovementController::class, 'edit'])->name('improvements.edit');
    Route::put('/improvements/{id}', [ImprovementController::class, 'update'])->name('improvements.update');
    Route::delete('/improvements/{id}', [ImprovementController::class, 'destroy'])->name('improvements.destroy');

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('custom.logout');

    Route::get('/home', [ProjetController::class, 'userProjects'])->name('mes-projets');
    Route::get('/projets/{id}', [ProjetController::class, 'showProjet'])->name('projets.show');

    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::post('/improvements', [ImprovementController::class, 'store'])->name('improvements.store');

    Route::resource('projets', ProjetController::class)->except(['create', 'edit']);
    Route::resource('comments', CommentController::class)->except(['create', 'edit']);
    Route::resource('issues', IssueController::class)->except(['create', 'edit']);
    Route::resource('improvements', ImprovementController::class)->except(['create', 'edit']);

    Route::post('/attachments', [AttachmentController::class, 'store'])->name('attachments.store');

    Route::middleware('role:admin')->prefix('teams')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('teams.index');       
        Route::post('/', [TeamController::class, 'store'])->name('teams.store');      
        Route::get('/{team}', [TeamController::class, 'show'])->name('teams.show');    
        Route::put('/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('teams.destroy'); 

        Route::get('/projets/create', [ProjetController::class, 'create'])->name('projets.create');
        Route::post('/projets', [ProjetController::class, 'store'])->name('projets.store');
    });
});
