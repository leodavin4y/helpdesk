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

/* База знаний */
Route::middleware(['auth'])->group(function () {
    Route::get('/faq', 'FaqController@viewItems')->name('faq');

    Route::post('/faq', 'FaqController@search')->name('faq.search');
});

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
});

Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin.index');

    Route::post('/admin/users/search', 'AdminController@usersSearch')->name('admin.users.search');

    Route::post('/admin/users/{id}/delete', 'AdminController@usersDelete')->name('admin.users.delete');

    Route::post('/admin/users/{id}/edit', 'AdminController@usersEdit')->name('admin.users.edit');
});
