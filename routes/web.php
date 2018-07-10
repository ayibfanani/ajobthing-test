<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/timeline', 'HomeController@timeline')->name('timeline');
    Route::get('/upgrade', 'HomeController@upgrade')->name('upgrade');
    Route::get('/jobs/add', 'JobController@create')->name('job.add');
    Route::get('/jobs/{id}/edit', 'JobController@edit')->name('job.edit');
    Route::get('/jobs/{id}/apply', 'JobController@getApply')->name('job.getApply');
    Route::get('/jobs/{id}/applier', 'JobController@getApplier')->name('job.getApplier');
});
