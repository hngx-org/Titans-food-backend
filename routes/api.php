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

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {


    Route::get('/search/{nameOrEmail}', [UserController::class, 'search'])->name('search.search');
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user_profile.index');
    Route::patch('/user/bank',[BankDetailController::class, 'addBankDetails'])->name('user.addBankDetails');
    Route::get('/user/all', [UserController::class, 'index'])->name('user.index');

    Route::post('/lunch', [LunchController::class,'store'])->name('lunch.store');
    Route::get('/lunch/{id}', [LunchController::class,'show'])->name('lunch.show');
    Route::get('/lunch', [LunchController::class,'index'])->name('lunch.index');
    Route::put('/organization/create',[OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/organization', [OrganizationController::class, 'getOrganization'])->name('organization.getOrganization');
    Route::post('/organization/invite', [OrganizationInviteController::class, 'store'])->name('organization_invite.store');

    Route::post('/withdrawal/request',[WithdrawalController::class,'store'])->name('withdrawal.store');
    Route::get('/withdrawal/request',[WithdrawalController::class,'index'])->name('withdrawal.index');

    Route::get('/bank_details', [BankDetailController::class, 'viewBankDetails'])->name('bank_details.viewBankDetails');
});

Route::prefix('v1')->group(function(){
    Route::post('/auth/user/signup', [OrganisationSignupController::class,'register'])->name('user.signup');
    Route::post('/auth/user/signin', [LoginController::class,'login'])->name('user.signin');
    Route::post('/organization/staff/signup', [OrganizationController::class, 'createOrganizationUser']);
});
