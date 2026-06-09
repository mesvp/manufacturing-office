<?php

Route::group(['prefix' => 'orderRequirement',  'middleware' => 'auth:admin'], function () {
    Route::any('orderRequirementList',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'orderRequirementList'])->name('orderRequirementList');
    Route::any('orderRequirementStockList',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'orderRequirementStockList']);
    Route::any('orderRequirementotherStockList',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'orderRequirementotherStockList']);
    Route::post('SalesFilter',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'SalesFilter']);
    Route::post('StockFilter',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'StockFilter']);
    Route::post('filtered',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'orderRequirementList']);
    Route::get('orderRequirement/{id?}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'AddorderRequirementPage']);
    Route::post('AddSales',[App\Http\Controllers\orderRequirement\orderRequirementController::class,'AddSales']);
    Route::post('AddStock',[App\Http\Controllers\orderRequirement\orderRequirementController::class,'AddStock']);
    Route::get('delete_Sales/{id}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'delete_Sales']);
    Route::get('delete_Stock/{id}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'delete_Stock']);
    Route::get('MaterialData/{id}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'MaterialData']);
    Route::get('MaterialCalculation/{id}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'MaterialCalculation']);
    Route::get('Sales_View/{id}/{type}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'Sales_View']);
    Route::get('Stock_View/{id}/{type}',[App\Http\Controllers\orderRequirement\orderRequirementViewController::class,'Stock_View']);
    Route::get('get-address/{productid}', [App\Http\Controllers\orderRequirement\orderRequirementViewController::class, 'getaddress']);
    Route::get('get-address-details_bill/{billid}', [App\Http\Controllers\orderRequirement\orderRequirementViewController::class, 'getaddressdetailsbill']);
    Route::get('get-address-details_ship/{shipid}', [App\Http\Controllers\orderRequirement\orderRequirementViewController::class, 'getaddressdetailsship']);

    Route::any('orderRequirementApproveList', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'orderRequirementApproveList']);
    Route::any('orderRequirementApproveStockList', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'orderRequirementApproveStockList']);
    //Route::post('Filter-approve', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'orderRequirementApproveList']);
    Route::get('Sales-view-approve/{id}/{type}', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Sales_view_approve']);
    Route::get('Stock-view-approve/{id}/{type}', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Stock_view_approve']);
    Route::post('Sales_approve', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Sales_approve']);
    Route::post('Stock_approve', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Stock_approve']);
    Route::get('Sales_Release_Hold/{id}', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Sales_Release_Hold']);
    Route::get('Stock_Release_Hold/{id}', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'Stock_Release_Hold']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\orderRequirement\orderRequirementApproveController::class, 'UpdateStatus']);
});