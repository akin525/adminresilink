<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
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
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('dashboard');

    Route::get('property', [PropertyController::class, 'property'])->name('property');
    Route::get('add-property', [PropertyController::class, 'addpro'])->name('add-property');
    Route::post('add-property', [PropertyController::class, 'addproperty'])->name('add-property');
    Route::get('allusers', [\App\Http\Controllers\UserController::class, 'alluser'])->name('allusers');
    Route::get('teams', [\App\Http\Controllers\TeamController::class, 'viewteam'])->name('teams');
    Route::post('teams', [\App\Http\Controllers\TeamController::class, 'addteam'])->name('teams');
});
