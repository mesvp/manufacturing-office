<?php

Route::group(['prefix' => 'BOM',  'middleware' => 'auth:admin'], function () {

    Route::get('BOMList', [App\Http\Controllers\BOM\BOMViewController::class, 'BOMList']);
    Route::post('filtered', [App\Http\Controllers\BOM\BOMViewController::class, 'BOMList']);
    Route::get('BOM/{id?}', [App\Http\Controllers\BOM\BOMViewController::class, 'AddBOM']);
    Route::get('BOM_View/{id}/{type}', [App\Http\Controllers\BOM\BOMViewController::class, 'BOM_View']);
    Route::post('AddBOM', [App\Http\Controllers\BOM\BOMController::class, 'AddBOM']);
    Route::get('delete/{id}', [App\Http\Controllers\BOM\BOMViewController::class, 'delete']);
    Route::get('BOMApproveList', [App\Http\Controllers\BOM\BOMApproveController::class, 'BOM_Approve']);
    Route::post('ApproveFilter', [App\Http\Controllers\BOM\BOMApproveController::class, 'BOM_Approve']);
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\BOM\BOMApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\BOM\BOMApproveController::class, 'approve']);
    Route::get('get-subproduct/{productid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubproduct']);
    Route::get('get-subsubproduct/{subproductid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubsubproduct']);    
    Route::Post('CheckBoxStore', [App\Http\Controllers\BOM\BOMViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\BOM\BOMViewController::class, 'getCheckBoxData']);
    Route::get('ExportData',[App\Http\Controllers\BOM\BOMViewController::class, 'ExportData']);
    Route::get('CheckHoldExpiry',[App\Http\Controllers\BOM\BOMApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus',[App\Http\Controllers\BOM\BOMApproveController::class, 'UpdateStatus']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\BOM\BOMApproveController::class, 'Release_Hold']);
});
