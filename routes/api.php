<?php

use App\Http\Controllers\LunchController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OrganisationSignupController;
use App\Http\Controllers\OrganizationInviteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;


Route::get('all', [LunchController::class, 'index']);

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
    Route::patch('/user/bank',[BankDetailController::class, 'addBankDetails'])->name('user.bank');

    Route::post('/organization/invite', [OrganizationInviteController::class, 'store']);
    Route::get('/user/profile', [ProfileController::class, 'index']);
});

Route::put('/organization/create',[OrganizationController::class, 'update']);

Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');


    Route::post('/organization/invite', [OrganizationInviteController::class, 'store']);
    Route::put('/organization/create',[OrganizationController::class, 'update']);
    Route::get('/organization', [OrganizationController::class, 'getOrganization']);

    Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');

    Route::post('/withdrawal/request',[WithdrawalController::class,'store']);
    Route::get('/withdrawal/request',[WithdrawalController::class,'index']);

    Route::get('/user/all', [UserController::class, 'index']);


    Route::get('/{user}/bank_details', [BankDetailController::class, 'viewBankDetails']);

    Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');
});

Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');

Route::post('/auth/user/signin', [LoginController::class,'login'])->name('user.signin');
Route::post('/organization/staff/signup', [OrganizationController::class, 'createOrganizationUser']);


Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');




