<?php

Route::apiResource('jobs', 'JobController');
Route::post('/jobs/{id}/apply', 'JobController@apply')->name('job.apply');
Route::post('/jobs/{id}/accept', 'JobController@accept')->name('job.accept');
Route::post('/jobs/{id}/markcomplete', 'JobController@markComplete')->name('job.markComplete');
Route::post('/jobs/{id}/cancel', 'JobController@cancel')->name('job.cancel');
