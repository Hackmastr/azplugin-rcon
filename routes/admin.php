<?php

use Illuminate\Support\Facades\Route;

Route::middleware('can:rcon.execute')->group(function () {
    Route::get('/', 'RconController@index')->name('index');
    Route::post('/{server?}', 'RconController@execute')->name('execute');
});
