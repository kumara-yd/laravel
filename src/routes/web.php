<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PreferenceController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'locale', 'first.user'])->name('dashboard');

Route::middleware(['auth','auth.admin', 'locale'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/navs', NavigationController::class);
    Route::resource('/preferences', PreferenceController::class);
    Route::put('/roles/{role}/permissions', [RoleController::class, 'givePermission'])->name('roles.permissions');
});

require __DIR__.'/auth.php';

Route::get('/change-locale/{locale}', function (string $locale) {
    session()->put('locale', $locale);
    return redirect()->back();
})->name('change-locale');

use App\Http\Controllers\Operator\HomeController;
use App\Http\Controllers\Operator\ProfileController as OperatorProfileController;

Route::middleware('auth')->group(function () {
    Route::resource('home', HomeController::class);
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::resource('profile', OperatorProfileController::class);
        Route::resource('users', HomeController::class);
    });
});
