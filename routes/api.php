<?php

use App\Http\Controllers\LunchController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OrganisationSignupController;
use App\Models\Organanization;
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
    Route::get('/search/{nameOrEmail}', [UserController::class, 'search'])->name('search.search');
    Route::post('/lunch', [LunchController::class,'store'])->name('lunch.store');
    Route::patch('/user/bank',[BankDetailController::class, 'addBankDetails'])->name('user.addBankDetails');
    Route::put('/organization/create',[OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/user/all', [UserController::class, 'index'])->name('user.index');
    Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');
    Route::get('/organization', [OrganizationController::class, 'getOrganization'])->name('organization.getOrganization');
});




Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');

Route::post('/auth/user/signin', [LoginController::class,'login'])->name('user.signin');



