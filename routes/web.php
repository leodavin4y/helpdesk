<?php

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

Route::get('/', 'HomeController@home')->name('home');

/* Регистрация и авторизация */
Route::get('/register', 'AuthController@register')->name('register');
Route::post('/register', 'AuthController@register');

Route::get('/login', 'AuthController@login')->name('login');
Route::post('/login', 'AuthController@login');

Route::post('/logout', 'AuthController@logout')->name('logout');

/* Группа маршрутов требующих авторизации */
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::post('/dashboard/request/store', 'DashboardController@storeRequest')->name('dashboard.request.store');

    Route::post('/dashboard/request/{id}/delete', 'DashboardController@deleteRequest')->name('dashboard.request.delete');

    Route::post('/dashboard/requests/{id}/solved', 'DashboardController@initiatorSolved')->name('dashboard.requests.solved');
});

Route::middleware(['auth', 'can:user'])->group(function () {
    Route::get('/user/dashboard', 'DashboardController@userBoard')->name('dashboard.user');
});

Route::middleware(['auth', 'can:worker'])->group(function () {
    Route::get('/worker/dashboard', 'DashboardController@workerBoard')->name('dashboard.worker');

    // Работник устанавливает статус заявки - "на проверке"
    Route::post('/dashboard/requests/{id}/done', 'DashboardController@workerDone')->name('dashboard.requests.worker.done');
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin.index');

    Route::post('/admin/users/search', 'AdminController@usersSearch')->name('admin.users.search');

    Route::post('/admin/users/{id}/delete', 'AdminController@usersDelete')->name('admin.users.delete');

    Route::post('/admin/users/{id}/edit', 'AdminController@usersEdit')->name('admin.users.edit');

    Route::any('/admin/dashboard', 'DashboardController@adminBoard')->name('dashboard.admin');

    Route::post('/dashboard/request/{id}/status', 'DashboardController@updateStatus')->name('dashboard.request.status');

    Route::get('/dashboard/users/{role}/get', 'DashboardController@getUsers')->name('dashboard.users.get');
});

