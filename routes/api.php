<?php

use App\Http\Controllers\LunchController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OrganisationSignupController;
use App\Http\Controllers\Auth\LoginController;


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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/search/{nameOrEmail}', [UserController::class, 'search']);
    Route::post('/lunch', [LunchController::class,'store'])->name('lunch.store');
});


Route::put('/organization/create',[OrganizationController::class, 'update']);

Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');

Route::post('/auth/user/signin', [LoginController::class,'login'])->name('user.signin');

Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');

Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');

