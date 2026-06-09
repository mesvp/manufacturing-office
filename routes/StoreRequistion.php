<?php

Route::group(['prefix' => 'StoreRequistion',  'middleware' => 'auth:admin'], function () {
    Route::get('StoreRequistionList',[App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class,'StoreRequistionList']);
    Route::post('filtered',[App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class,'StoreRequistionList']);
    Route::get('AddStoreRequistion/{id?}',[App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class,'AddStoreRequistion']);
    Route::post('AddStoreRequistion',[App\Http\Controllers\StoreRequistion\StoreRequistionController::class,'AddStoreRequistion']);
    Route::get('delete/{id}',[App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class,'delete']);
    Route::get('StoreRequistionApproveList',[App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'StoreRequistion_approve']);
    Route::post('ApproveFilter',[App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'StoreRequistion_approve']);
    Route::get('view-approve/{id}/{type}',[App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'view_approve']);
    Route::post('approve',[App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'approve']);  
    Route::get('StoreRequistion_View/{id}/{type}', [App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class, 'StoreRequistion_View']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'Release_Hold']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\StoreRequistion\StoreRequistionApproveController::class, 'UpdateStatus']);
    Route::Post('CheckBoxStore', [App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class, 'getCheckBoxData']);
    Route::get('ExportData', [App\Http\Controllers\StoreRequistion\StoreRequistionViewController::class, 'ExportData']);
});
