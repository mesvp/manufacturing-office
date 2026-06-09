<?php

Route::group(['prefix' => 'StockTransfer',  'middleware' => 'auth:admin'], function () {
    Route::any('TransferRequestList', [App\Http\Controllers\StockTransfer\StockTransferViewController::class, 'TransferRequestList']);
    Route::post('filtered',[App\Http\Controllers\StockTransfer\StockTransferViewController::class,'TransferRequestList']);
    Route::get('AddStoreTransfer/{id?}',[App\Http\Controllers\StockTransfer\StockTransferViewController::class,'AddStoreTransfer']);
    Route::post('StoreStockTransfer',[App\Http\Controllers\StockTransfer\StoreTransferController::class,'StoreStockTransfer']);
    Route::get('delete/{id}',[App\Http\Controllers\StockTransfer\StockTransferViewController::class,'delete']);
    Route::get('ApprovalList',[App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'StoreTransfer_approve']);
    Route::post('ApproveFilter',[App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'StoreTransfer_approve']);
    Route::get('view-approve/{id}/{type}',[App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'view_approve']);
    Route::post('approve',[App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'approve']);  
    Route::get('StoreTransfer_View/{id}/{type}', [App\Http\Controllers\StockTransfer\StockTransferViewController::class, 'StoreTransfer_View']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'Release_Hold']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'UpdateStatus']);
    Route::Post('CheckBoxStore', [App\Http\Controllers\StockTransfer\StockTransferViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\StockTransfer\StockTransferViewController::class, 'getCheckBoxData']);
    Route::get('ExportData', [App\Http\Controllers\StockTransfer\StockTransferViewController::class, 'ExportData']);
    Route::get('ExportaprvData', [App\Http\Controllers\StockTransfer\StoreTransferApproveController::class, 'ExportaprvData']);
    Route::post('CheckSerialNumber', [App\Http\Controllers\StockTransfer\StoreTransferController::class, 'checkSerialNumber']);
});
