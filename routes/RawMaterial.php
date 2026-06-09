<?php

Route::group(['prefix' => 'RawMaterial',  'middleware' => 'auth:admin'], function () {

    Route::get('RawMaterialList', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'RawMaterialList']);
    Route::post('filtered', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'RawMaterialList']);
    Route::get('RawMaterial/{id?}', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'AddRawMaterial']);
    Route::get('RawMaterial_View/{id}/{type}', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'RawMaterial_View']);
    Route::Post('AddRawMaterial', [App\Http\Controllers\RawMaterial\RawMaterialController::class, 'AddRawMaterial']);
    Route::get('get-matdetailsajax/{mid}',[App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'getmateraildeatsilsajax']);
    Route::get('delete/{id}', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'delete']);
    Route::get('RawMaterialApproveList', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'RawMaterial_approve']);
    Route::post('ApproveFilter', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'RawMaterial_approve']);
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'approve']);

    Route::Post('CheckBoxStore', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'getCheckBoxData']);
    Route::get('FilterData', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'FilterData']);
    Route::get('ExportFilteredData', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'DownloadFilteredData']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'UpdateStatus']);

    Route::get('Release_Hold/{id}', [App\Http\Controllers\RawMaterial\RawMaterialApproveController::class, 'Release_Hold']);

    Route::get('MaterialData/{id}', [App\Http\Controllers\RawMaterial\RawMaterialViewController::class, 'MaterialData']);
});
