<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::post('register', 'register')->name('register');
        Route::get('verifyEmail/{token}', 'verifyEmail')->name('verifyEmail');
    });

//    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
//        Route::get('reset', 'showLinkRequestForm')->name('request');
//        Route::post('email', 'sendResetCodeEmail')->name('email');
//        Route::get('code-verify', 'codeVerify')->name('code.verify');
//        Route::post('verify-code', 'verifyCode')->name('verify.code');
//    });
//    Route::controller('ResetPasswordController')->group(function () {
//        Route::post('password/reset', 'reset')->name('password.update');
//        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
//    });


Route::middleware(['jwt.verify'])->group(function () {
    Route::controller(PropertyController::class)->group(function () {
        Route::post('createproperty', 'create')->name('createproperties');
        Route::get('properties', 'fetchproperties')->name('fetchproperties');
        Route::get('property/{id}', 'fetchpropertiesbyid')->name('fetchpropertiesbyid');
    });
    Route::controller(TeamController::class)->group(function () {
        Route::get('teams', 'fetchteams')->name('teams');
        Route::get('team/{id}', 'fetchteamsbyid')->name('teambyid');
    });
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'fetchusers')->name('fetchusers');
        Route::get('agents', 'fetchagent')->name('fetchagent');
        Route::get('user/{id}', 'fetchuserbyid')->name('fetchuserbyid');
    });
    Route::controller(AboutController::class)->group(function () {
        Route::get('aboutus', 'fetchaboutus')->name('fetchaboutus');
    });
});
