<?php

use App\Http\Controllers\TableController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'global_middleware'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('auth.login');

    Route::post('/login_action', [AuthController::class, 'login_action'])->name('auth.login_action');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::group(['middleware' => 'front_middleware'], function () {



    Route::get('/get_profile', [AuthController::class, 'getProfile'])->name('auth.get_profile');
    Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::put('/update_profile', [AuthController::class, 'updateProfile'])->name('auth.update_profile');
    Route::get('/change_password', [AuthController::class, 'changePassword'])->name('auth.change_password');
    Route::put('/update_password', [AuthController::class, 'updatePassword'])->name('auth.update_password');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/get_chart_order', [DashboardController::class, 'getChartOrder'])->name('get_chart_order');
        Route::get('/get_chart_income', [DashboardController::class, 'getChartIncome'])->name('get_chart_income');
        Route::get('/get_chart_customer', [DashboardController::class, 'getChartCustomer'])->name('get_chart_customer');
    });

    Route::prefix('table')->name('table.')->group(function () {
        Route::get('/', [TableController::class, 'index'])->name('index');

        Route::get('/ajax_get_table', [TableController::class, 'ajaxGetTable'])->name('ajax_get_table');
        Route::get('/{id}', [TableController::class, 'detail'])->name('detail');
        Route::post('/', [TableController::class, 'store'])->name('store');
        Route::put('/{id}', [TableController::class, 'update'])->name('update');
        Route::delete('/{id}', [TableController::class, 'delete'])->name('delete');
    });

    Route::prefix('meal')->name('meal.')->group(function () {
        Route::get('/', [MealController::class, 'index'])->name('index');

        Route::get('/ajax_get_meal', [MealController::class, 'ajaxGetMeal'])->name('ajax_get_meal');
        Route::get('/{id}', [MealController::class, 'detail'])->name('detail');
        Route::post('/', [MealController::class, 'store'])->name('store');
        Route::put('/{id}', [MealController::class, 'update'])->name('update');
        Route::delete('/{id}', [MealController::class, 'delete'])->name('delete');
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/{id}', [CustomerController::class, 'detail'])->name('detail');
        Route::post('/store', [CustomerController::class, 'store'])->name('store');
        Route::delete('/delete', [CustomerController::class, 'delete'])->name('delete');
    });

    Route::prefix('transaction')->name('transaction.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/get_transaction', [TransactionController::class, 'getTransactionList'])->name('get_transaction');
        Route::get('/{id}', [TransactionController::class, 'detail'])->name('detail');
        Route::put('/{id}', [TransactionController::class, 'update'])->name('update');
        Route::post('/store', [TransactionController::class, 'store'])->name('store');
        Route::delete('/delete', [TransactionController::class, 'delete'])->name('delete');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/income_chart', [ReportController::class, 'incomeChart'])->name('income_chart');
        Route::get('/customer_chart', [ReportController::class, 'customerChart'])->name('customer_chart');
        Route::get('/count', [ReportController::class, 'ajaxCountChart'])->name('ajax_count_chart');
    });

    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{id}', [RoleController::class, 'detail'])->name('detail');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
    });

    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/{id}', [MenuController::class, 'detail'])->name('detail');
        Route::post('/store', [MenuController::class, 'store'])->name('store');
    });

    Route::prefix('bonus')->name('bonus.')->group(function () {
        Route::get('/', [BonusController::class, 'index'])->name('index');
    });
});
