<?php

Route::group(['prefix' => 'ProductionProcess',  'middleware' => 'auth:admin'], function () {
    Route::get('ProductionProcessList',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'ProductionProcessList']);
    Route::post('filtered',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'ProductionProcessList']);
    Route::get('ProductionProcess/{id?}',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'AddProductionProcess']);
    Route::post('AddProductionProcess',[App\Http\Controllers\ProductionProcess\ProductionProcessController::class,'AddProductionProcess']);
    Route::get('ProductStage/{id}',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'ProductStage']);  
    Route::post('AddStages',[App\Http\Controllers\ProductionProcess\ProductionProcessController::class,'AddStages']);
    Route::get('ProductionProcess_View/{id}/{type}',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'ProductionProcess_View']);
    Route::get('delete/{id}',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class,'delete']);
    Route::get('ProductionProcessApproveList', [App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'ProductionProcessApprove']);
    Route::post('Filter-approve',[App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'ProductionProcessApprove']);
    Route::get('view-approve/{id}/{type}',[App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'view_approve']);
    Route::post('approve',[App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'approve']);
    Route::get('get-subproduct/{productid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubproduct']);
    Route::get('get-subsubproduct/{subproductid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubsubproduct']);
    Route::Post('CheckBoxStore', [App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class, 'getCheckBoxData']);
    Route::get('ExportData',[App\Http\Controllers\ProductionProcess\ProductionProcessViewController::class, 'ExportData']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'Release_Hold']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\ProductionProcess\ProductionProcessApproveController::class, 'UpdateStatus']);
});