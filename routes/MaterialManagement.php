<?php

Route::group(['prefix' => 'MaterialManagement',  'middleware' => 'auth:admin'], function () {
    Route::get('MaterialList',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class,'MaterialList']);
    Route::post('filterMaterial',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class,'MaterialList']);
    Route::get('AddMaterial/{id?}',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class,'AddMaterial']);
    Route::get('get-materialdetailsajax/{vid}',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class, 'getmateraildeatsilsajax']);
    Route::get('Material_view/{id}/{type}',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class,'Material_view']);
    Route::post('AddMaterial',[App\Http\Controllers\MaterialManagement\MaterialManagementController::class,'AddMaterial']);
    Route::get('delete/{id}',[App\Http\Controllers\MaterialManagement\MaterialManagementController::class,'delete']);
    Route::get('MaterialApproveList',[App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class,'MaterialApproveList']);
    Route::post('filtered',[App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class,'MaterialApproveList']); 
    Route::post('Filter-approve', [App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'MaterialApproveList']);
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'MaterialApproveView']);
    Route::post('approve', [App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'approve']);
    Route::Post('CheckBoxStore', [App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class, 'getCheckBoxData']);
    Route::get('ExportFilteredData',[App\Http\Controllers\MaterialManagement\MaterialManagementViewController::class, 'DownloadFilteredData']);
    Route::get('CheckHoldExpiry',[App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus',[App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'UpdateStatus']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\MaterialManagement\MaterialManagementApproveController::class, 'Release_Hold']);

});
