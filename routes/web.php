<?php

use Illuminate\Support\Facades\Route;

/** Other Route */
Route::get('/', function () { return redirect()->route('auth.home'); })->name('home');
Route::get('/record/{code}', 'RecordController@show')->name('record.link');

/**
 * Admin
 */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth'
], function () {
    Route::get('/', function () { return view('pages.admin.home'); })->name('home');
    Route::resource('/user', 'Admin\UserController')->except(['create', 'edit', 'show']);
    Route::resource('/medicine', 'Admin\MedicineController')->except(['create', 'edit', 'show']);
    Route::post('/medicine/import', 'Admin\MedicineController@import')->name('medicine.import');
    Route::resource('/patient', 'Admin\PatientController')->except(['create', 'edit', 'show']);
    Route::resource('/recipe', 'Admin\RecipeController')->except(['create', 'edit', 'show']);
    Route::resource('/record', 'Admin\RecordController')->except(['create', 'edit', 'show']);
});

/**
 * Auth routes
 */
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
], function () {
    Route::get('/login', 'AuthController@login')->name('login')->middleware('guest');
    Route::post('/login', 'AuthController@login_post')->name('login.post')->middleware('guest');
    Route::post('/logout', 'AuthController@logout')->name('logout')->middleware('auth');
    Route::get('/home', 'AuthController@home')->name('home')->middleware('auth');
});
