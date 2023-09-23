<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LunchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationInviteController;
use App\Http\Controllers\Auth\OrganisationSignupController;
use App\Http\Controllers\OrganizationLunchWalletController;
use App\Http\Controllers\OrganizationRefreshController;


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

Route::middleware('auth:api')->prefix('v1')->group(function () {


    Route::get('/user/search/{nameOrEmail}', [UserController::class, 'search'])->name('search.search');
    Route::get('/user/profile', [ProfileController::class, 'index'])->name('user_profile.index');
    Route::patch('/user/bank', [BankDetailController::class, 'addBankDetails'])->name('user.addBankDetails');
    Route::get('/user/all', [UserController::class, 'index'])->name('user.index');

    Route::post('/lunch', [LunchController::class, 'store'])->name('lunch.store');
    Route::patch('/user/bank', [BankDetailController::class, 'addBankDetails'])->name('user.bank');

    Route::patch('/organization/lunch-price', [OrganizationController::class, 'update_lunch_price']);
    Route::post('/organization/invite', [OrganizationInviteController::class, 'store']);
    Route::put('/organization/create', [OrganizationController::class, 'update']);
    Route::post('/organization/create', [OrganizationController::class, 'store']);
    Route::get('/organization', [OrganizationController::class, 'getOrganization']);

    Route::patch('/wallet', [OrganizationLunchWalletController::class, 'update'])->name('wallet.update');
    Route::get('/lunch/{id}', [LunchController::class, 'show'])->name('lunch.show');
    Route::get('/lunch', [LunchController::class, 'index'])->name('lunch.index');
    Route::put('/organization/create', [OrganizationController::class, 'store'])->name('organization.store');
    Route::get('/organization', [OrganizationController::class, 'getOrganization'])->name('organization.getOrganization');
    Route::post('/organization/invite', [OrganizationInviteController::class, 'store'])->name('organization_invite.store');

    Route::post('/withdrawal/request', [WithdrawalController::class, 'store'])->name('withdrawal.store');
    Route::get('/withdrawal/request', [WithdrawalController::class, 'index'])->name('withdrawal.index');

    Route::get('/bank_details', [BankDetailController::class, 'viewBankDetails'])->name('bank_details.viewBankDetails');

    Route::post('/logout', [OrganizationRefreshController::class, 'logout'])->name('user.logout');
    Route::post('/token/refresh', [OrganizationRefreshController::class, 'refreshToken'])->name('user.refresh_token');
});

Route::prefix('v1')->group(function () {
    Route::post('/auth/user/signup', [OrganisationSignupController::class, 'register'])->name('user.signup');
    Route::post('/auth/user/signin', [LoginController::class, 'login'])->name('user.signin');
    Route::post('/auth/user/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->name('user.forgotPassword');
    Route::post('/organization/staff/signup', [OrganizationController::class, 'createOrganizationUser']);
});
