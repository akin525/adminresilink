<?php

use App\Http\Controllers\AuthController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard',[AuthController::class, 'dashboard'])->name('dashboard');

    Route::get('property', [AuthController::class, 'property'])->name('property');
    Route::get('add-property', [AuthController::class, 'addpro'])->name('add-property');
    Route::post('add-property', [AuthController::class, 'addproperty'])->name('add-property');
});
