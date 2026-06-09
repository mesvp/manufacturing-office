<?php

Route::group(['prefix' => 'InventoryManagement',  'middleware' => 'auth:admin'], function () {
    Route::any('InventoryManagementList', [App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'InventoryManagementList']);
    //Route::post('filtered', [App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'InventoryManagementList']);
    Route::get('InventoryManagement/{id?}', [App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'AddInventoryManagement']);
    Route::post('AddInventoryManagement', [App\Http\Controllers\InventoryManagement\InventoryManagementController::class, 'AddInventoryManagement']);
    //Route::get('delete/{id}', [App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'delete']);
    Route::get('InventoryManagementApproverList',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class, 'InventoryManagementList']);
    //Route::post('ApproveFilter',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class, 'InventoryManagement_approve']);
    //Route::get('view-approve/{id}',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class, 'view_approve']);
    Route::post('approve',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class, 'approve']); 
    //////////////////////////////////// 
    Route::post('Formview',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'formview']);
    Route::post('action/{type?}',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'action']);
    Route::post('trail',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'trail']);
    Route::get('ApproverView/{id}',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class,'view']);
    Route::get('InputerView/{id}',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'view']);
    Route::post('approve',[App\Http\Controllers\InventoryManagement\InventoryManagementApproveController::class,'approve']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'Release_Hold']);
    Route::get('exportdata',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class,'exportdata']);
    ////////////////////////////////////////
    Route::post('fetchbatch',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'FetchBatchData']);  
    Route::post('fetchbatchoption',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'FetchBatch']);  
    Route::post('manage',[App\Http\Controllers\InventoryManagement\InventoryManagementViewController::class, 'ManageData']);  
});
