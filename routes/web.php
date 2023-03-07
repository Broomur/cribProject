<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
 * Home route
 */

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/*
 * Email verification routes
 */

Route::get('/verify-email', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware('auth')
    ->name('verification.verify');

/*
 * Reset password routes
 */

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])          
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordresetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])
    ->middleware('guest')
    ->name('password.update');

/*
 * Dashboard route
 */

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'has.role:3', 'verified'])
    ->name('dashboard');

/*
 * Admin route
 */

Route::resource('admin', AdminController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:10']);

Route::get('admin/account/{user}', [AdminController::class, 'show'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.account')
    ->missing( fn() => redirect('admin')->with('error', 'Cet utilisateur n\'existe pas...'));

Route::get('admin/edit/{hometext}', [AdminController::class, 'edit'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.edit')
    ->missing( fn() => redirect('admin')->with('error', 'Ce contenu n\'existe pas...'));

Route::post('admin/update/{hometext}', [AdminController::class, 'update'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.update')
    ->missing( fn() => redirect('admin')->with('error', 'Ce contenu n\'existe pas...'));

Route::put('admin/activate_parent/{user}', [AdminController::class, 'activate_parent'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.activate_parent')
    ->missing( fn() => redirect('admin')->with('error', 'Cet utilisateur n\'existe pas...'));

Route::put('admin/activate_pro/{user}', [AdminController::class, 'activate_pro'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.activate_pro')
    ->missing( fn() => redirect('admin')->with('error', 'Cet utilisateur n\'existe pas...'));

Route::put('admin/destroy/{user}', [AdminController::class, 'destroy'])
    ->middleware(['auth', 'has.role:10'])
    ->name('admin.destroy')
    ->missing( fn() => redirect('admin')->with('error', 'Cet utilisateur n\'existe pas...'));

/*
 * Account options routes
 */

Route::get('user/edit/{user}', [UserController::class, 'edit'])
    ->middleware(['auth', 'has.role:3', 'verify.id'])
    ->name('account_options')
    ->missing( fn() => redirect('dashboard')->with('error', 'Cet utilisateur n\'existe pas...'));

Route::put('user/update/{user}', [UserController::class, 'update'])
    ->middleware(['auth', 'has.role:3'])
    ->name('user.update');

Route::put('user/passwd/{user}', [UserController::class, 'passwdUpdate'])
    ->middleware(['auth', 'has.role:3'])
    ->name('user.passwd');
    
Route::put('user/userDestroy/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth', 'has.role:3'])
    ->name('user.destroy');

/*
 * Albums routes
 */

Route::resource('albums', AlbumController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:3']);

Route::get('albums/show/{album}', [AlbumController::class, 'show'])
    ->middleware(['auth', 'has.role:3'])
    ->name('albums.show')
    ->missing( fn() => redirect('albums')->with('error', 'Cet album n\'existe pas.'));

Route::get('albums/delete/{album}', [AlbumController::class, 'destroy'])
    ->middleware(['auth', 'has.role:9'])
    ->name('albums.delete')
    ->missing( fn() => redirect('albums')->with('error', 'Cet album n\'existe pas.'));

Route::resource('albums', AlbumController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'has.role:9']);

Route::post('albums/update/{album}', [AlbumController::class, 'update'])
    ->middleware(['auth', 'has.role:9'])
    ->name('albums.update')
    ->missing( fn() => redirect('albums')->with('error', 'Cet album n\'existe pas.'));

Route::get('albums/picture/delete/{album}/{picture}', [AlbumController::class, 'pictureDestroy'])
    ->middleware(['auth', 'has.role:9'])
    ->name('albums.picture.delete')
    ->missing( fn() => redirect('albums')->with('error', 'Cette photo n\'existe pas.'));

/*
 * Teamates routes
 */

Route::resource('team', TeamController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:3']);

Route::resource('team', TeamController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'has.role:9']);

Route::post('team/update/{team}', [TeamController::class, 'update'])
    ->middleware(['auth', 'has.role:9'])
    ->name('team.update')
    ->missing( fn() => redirect('team')->with('error', 'Cet utilisateur n\'existe pas.'));
    
/*
 * Calendar routes
 */

Route::resource('calendar', CalendarController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:3']);

Route::resource('calendar', CalendarController::class)
    ->only(['create', 'store', 'update'])
    ->middleware(['auth', 'has.role:4']);

Route::get('calendar/show/{calendar}', [CalendarController::class, 'show'])
    ->middleware(['auth', 'has.role:3'])
    ->name('calendar.show')
    ->missing( fn () => redirect('calendar')->with('error', 'Cet événement n\'existe pas.'));

Route::get('calendar/delete/{calendar}', [CalendarController::class, 'destroy'])
    ->middleware(['auth', 'has.role:4'])
    ->name('calendar.delete')
    ->missing( fn () => redirect('calendar')->with('error', 'Cet événement n\'existe pas.'));

/*
 * Menus routes
 */

Route::resource('menus', MenuController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:3']);

Route::resource('menus', MenuController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'has.role:8']);

/*
 * Articles routes
 */

Route::resource('articles', ArticleController::class)
    ->only(['index'])
    ->middleware(['auth', 'has.role:3']);

Route::resource('articles', ArticleController::class)
    ->only(['create', 'store', 'update'])
    ->middleware(['auth', 'has.role:4']);

Route::get('articles/show/{article}', [ArticleController::class, 'show'])
    ->middleware(['auth', 'has.role:3'])
    ->name('articles.show')
    ->missing( fn() => redirect('articles')->with('error', 'Cet article n\'existe pas.'));

Route::get('articles/delete/{article}', [ArticleController::class, 'destroy'])
    ->middleware(['auth', 'has.role:4'])
    ->name('articles.delete')
    ->missing( fn() => redirect('articles')->with('error', 'Cet article n\'existe pas.'));

/* 
 * Documents route
 */

Route::resource('documents', DocumentController::class)
    ->only('index')
    ->middleware(['auth', 'has.role:3']);

Route::resource('documents', DocumentController::class)
    ->only([ 'create', 'store' ])
    ->middleware(['auth', 'has.role:10' ]);

Route::get('documents/delete/{document}', [DocumentController::class, 'destroy'])
    ->middleware([ 'auth', 'has.role:10' ])
    ->name('documents.delete')
    ->missing( fn() => redirect('documents')->with('error', 'Le document n\existe pas.'));

require __DIR__.'/auth.php';