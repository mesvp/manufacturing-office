<?php
Route::group(['prefix' => 'PPFinishedGood',  'middleware' => 'auth:admin'], function () {

    Route::get('PPFinishedGoodList', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'PPFinishedGoodList']);
    Route::post('filtered', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'PPFinishedGoodList']);
    Route::get('PPFinishedGood/{id?}', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'AddPPFinishedGood']);
    Route::get('PPFinishedGood_view/{id}/{type}', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'PPFinishedGood_View']);
    Route::Post('AddPPFinishedGood', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodController::class, 'AddPPFinishedGood']);
    Route::get('delete/{id}', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'delete']);
    Route::get('PPFinishedGoodApproveList', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'PPFinishedGood_approve']);
    Route::post('ApproveFilter',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'PPFinishedGood_approve']);
    Route::get('view-approve/{id}/{type}',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'view_approve']);
    Route::post('approve',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'approve']);
    Route::get('get-subproduct/{productid}',[App\typeHttp\Controllers\FactoryCreater\FactoryViewController::class, 'getsubproduct']);
    Route::get('get-plantnamedetails/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getplantnamedetails']);
    Route::get('get-budetails/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getbudetails']);
    Route::get('get-orgnames/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getorgnames']);
    Route::get('get-subsubproduct/{subproductid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubsubproduct']);    
    Route::Post('CheckBoxStore', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'getCheckBoxData']);
    Route::get('ExportData',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodViewController::class, 'ExportData']);
    Route::get('CheckHoldExpiry',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus',[App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'UpdateStatus']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\PPFinishedGood\PPFinishedGoodApproveController::class, 'Release_Hold']);
});
