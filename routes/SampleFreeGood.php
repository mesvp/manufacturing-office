<?php

Route::group(['prefix' => 'SampleFreeGood',  'middleware' => 'auth:admin'], function () {

    Route::any('SampleFreeGoodList', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'SampleFreeGoodList']);
    Route::post('filtered', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'SampleFreeGoodList']);
    Route::get('SampleFreeGood/{id?}', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'AddSampleFreeGood']);
    Route::Post('AddSampleFreeGood', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodController::class, 'AddSampleFreeGood']);
    Route::get('delete/{id}', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'delete']);
    Route::get('remove-file/{id}', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'DeleteAttachment']);
    Route::any('SampleFreeGoodApproveList', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class, 'SampleFreeGood_approve']);
    Route::post('RawmaterialData', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'RawmaterialData']);
    Route::post('RawmaterialgetData', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'RawmaterialgetData']);
    Route::post('Rawmaterialgetsl', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'Rawmaterialgetsl']);
    ////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('ApproverView/{id}',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class,'view']);
    Route::get('InputerView/{id}',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'view']);
    Route::post('approve',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class,'approve']);
    Route::post('Formview',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'formview']);
    Route::post('action/{type?}',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'action']);
    Route::post('trail',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'trail']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'Release_Hold']);
    Route::get('exportdata',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class,'exportdata']);
    Route::get('get-customerdetails/{id}', [App\Http\Controllers\SampleFreeGood\SampleFreeGoodViewController::class, 'getcustomerdetails']);
    
    

    // Route::post('ApproveFilter',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class, 'SampleFreeGood_approve']);
    // Route::get('view-approve/{id}',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class, 'view_approve']);
    // Route::post('approve',[App\Http\Controllers\SampleFreeGood\SampleFreeGoodApproveController::class, 'approve']);
});
