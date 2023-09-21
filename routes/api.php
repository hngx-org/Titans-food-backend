<?php

use App\Http\Controllers\LunchController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OrganisationSignupController;

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
});


Route::post('/organization/create',[OrganizationController::class, 'store']);

Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');

Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');


