<?php
use Illuminate\Support\Facades\Route;

// Route::get('greeting', function () {
//     return 'Hi, this is your awesome package! Mcqt';
// });

// Route::get('picmatch/test', 'EdgeWizz\Picmatch\Controllers\PicmatchController@test')->name('test');

Route::post('fmt/mcqt/store', 'EdgeWizz\Mcqt\Controllers\McqtController@store')->name('fmt.mcqt.store');

Route::post('fmt/mcqt/csv', 'EdgeWizz\Mcqt\Controllers\McqtController@csv_upload')->name('fmt.mcqt.csv');

Route::post('fmt/mcqt/update/{id}', 'EdgeWizz\Mcqt\Controllers\McqtController@edit')->name('fmt.mcqt.update');

Route::any('fmt/mcqt/inactive/{id}',  'EdgeWizz\Mcqt\Controllers\McqtController@inactive')->name('fmt.mcqt.inactive');
Route::any('fmt/mcqt/active/{id}',  'EdgeWizz\Mcqt\Controllers\McqtController@active')->name('fmt.mcqt.active');