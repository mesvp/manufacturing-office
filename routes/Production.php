<?php

Route::group(['prefix' => 'Production',  'middleware' => 'auth:admin'], function () {
    Route::any('ProductionList',[App\Http\Controllers\Production\ProductionViewController::class,'ProductionList']);
    Route::post('Formview',[App\Http\Controllers\Production\ProductionViewController::class,'formview']);
    Route::post('action/{type?}',[App\Http\Controllers\Production\ProductionViewController::class,'action']);
    Route::post('trail',[App\Http\Controllers\Production\ProductionViewController::class,'trail']);
    Route::any('ProductionApproverList',[App\Http\Controllers\Production\ProductionApproverViewController::class,'ProductionList']);
    Route::get('ApproverView/{id}',[App\Http\Controllers\Production\ProductionApproverViewController::class,'view']);
    Route::get('InputerView/{id}',[App\Http\Controllers\Production\ProductionViewController::class,'view']);
    Route::post('approve',[App\Http\Controllers\Production\ProductionApproverViewController::class,'approve']);
    Route::get('ProductionView/{id}',[App\Http\Controllers\Production\ProductionViewController::class,'ProductionList']);
    Route::post('MaterialData',[App\Http\Controllers\Production\ProductionViewController::class,'MaterialData']);
    Route::post('MaterialData_view',[App\Http\Controllers\Production\ProductionViewController::class,'MaterialData_view']);
    Route::post('StoreData',[App\Http\Controllers\Production\ProductionController::class,'store'])->name('Production.store');
  //  Route::post('filtered',[App\Http\Controllers\Production\ProductionViewController::class,'ProductionList']);
    Route::get('Production/{id?}',[App\Http\Controllers\Production\ProductionViewController::class,'AddProductionPage']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\Production\ProductionViewController::class,'Release_Hold']);
    Route::get('ExportData',[App\Http\Controllers\Production\ProductionViewController::class,'ExportProduction']);
    Route::get('get-serialnumberdetails/{id}', [App\Http\Controllers\Production\ProductionViewController::class, 'getserialnumberdetails']);

    // Route::post('AddSales',[App\Http\Controllers\Production\ProductionController::class,'AddSales']);
    // Route::post('AddStock',[App\Http\Controllers\Production\ProductionController::class,'AddStock']);
    // Route::get('delete_Sales/{id}',[App\Http\Controllers\Production\ProductionViewController::class,'delete_Sales']);
    // Route::get('delete_Stock/{id}',[App\Http\Controllers\Production\ProductionViewController::class,'delete_Stock']);
    
});