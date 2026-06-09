<?php

Route::group(['prefix' => 'Dashboard',  'middleware' => 'auth:admin'], function () {

    Route::get('dashboard', [App\Http\Controllers\Dashboard\DashboardViewController::class, 'dashboard']);

});
