<?php

use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth'
], function () {
    Route::get('/', function () { return view('pages.admin.home'); })->name('home');
    Route::resource('/medicine', 'Admin\MedicineController')->except(['create', 'edit', 'show']);
    Route::resource('/patient', 'Admin\PatientController')->except(['create', 'edit', 'show']);
    Route::resource('/recipe', 'Admin\RecipeController')->except(['show']);
});

/**
 * Auth routes
 */
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'middleware' => 'auth'
], function () {
    Route::get('/login', 'AuthController@login')->name('login')->withoutMiddleware('auth')->middleware('guest');
    Route::post('/login', 'AuthController@login_post')->name('login.post')->withoutMiddleware('auth')->middleware('guest');
    Route::post('/logout', 'AuthController@logout')->name('logout');
    Route::get('/home', 'AuthController@home')->name('home');
});
