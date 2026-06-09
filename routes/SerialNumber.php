<?php

Route::group(['prefix' => 'SerialNumber',  'middleware' => 'auth:admin'], function () {
  
    Route::any('SerialnumberList',[App\Http\Controllers\SerialNumber\SerailNumberViewController::class,'SerialnumberList']);
    Route::get('SerialNumber_View/{id}/{type}', [App\Http\Controllers\SerialNumber\SerailNumberViewController::class, 'SerialNumber_View']);
    Route::get('AddSerialNumber',[App\Http\Controllers\SerialNumber\SerailNumberViewController::class,'AddSerialNumber']);
    Route::get('AddSerialNumber1',[App\Http\Controllers\SerialNumber\SerailNumberViewController::class,'AddSerialNumber1']);
    Route::post('store',[App\Http\Controllers\SerialNumber\SerailNumberController::class,'store'])->name('SerialNumber.store');
    Route::post('store1',[App\Http\Controllers\SerialNumber\SerailNumberController::class,'store1'])->name('SerialNumber.store1');
    Route::post('filtered', [App\Http\Controllers\SerialNumber\SerailNumberViewController::class, 'SerialnumberList']);
    Route::get('SerialApproveList', [App\Http\Controllers\SerialNumber\SerialNumberApproveController::class, 'serialApprove']);
    Route::get('getCheckBoxData', [App\Http\Controllers\SerialNumber\SerailNumberViewController::class, 'getCheckBoxData']);
    
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\SerialNumber\SerialNumberApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\SerialNumber\SerialNumberApproveController::class, 'approve']);
    Route::post('Filter-approve', [App\Http\Controllers\SerialNumber\SerialNumberApproveController::class, 'serialApprove']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\SerialNumber\SerialNumberApproveController::class, 'Release_Hold']);
    Route::get('ExportData', [App\Http\Controllers\SerialNumber\SerailNumberViewController::class, 'ExportData']);


});