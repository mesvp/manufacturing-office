<?php
Route::group(['prefix' => 'QCSampleTesting',  'middleware' => 'auth:admin'], function () {

    Route::any('STDFinishedGoodsList', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'STDFinishedGoodsList']);
    Route::any('STDFinishedGoodsApproverList', [App\Http\Controllers\QCSampleTesting\QCApproverController::class, 'STDFinishedGoodsList']);
    //Route::post('filtered', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'STDFinishedGoodsList']);
    Route::get('STDFinishedGoods/{id?}', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'AddSTDFinishedGoods']);
    Route::Post('AddSTDFinishedGoods', [App\Http\Controllers\QCSampleTesting\QCSampleTestingController::class, 'AddSTDFinishedGoods'])->name('QCSampleTesting.store');
    Route::Post('fetchbatch', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'FetchBatch']);
    Route::Post('fetchbatchdata', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'FetchBatchData']);
    Route::Post('fetchbatchfordata', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'FetchBatchDatafor']);
    ////////////////////
    Route::post('Formview',[App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class,'formview']);
    Route::post('action/{type?}',[App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class,'action']);
    Route::post('trail',[App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class,'trail']);
    Route::get('ApproverView/{id}',[App\Http\Controllers\QCSampleTesting\QCApproverController::class,'view']);
    Route::get('InputerView/{id}',[App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class,'view']);
    Route::post('approve',[App\Http\Controllers\QCSampleTesting\QCApproverController::class,'approve']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class,'Release_Hold']);
    
    //////////////////////////////////
    Route::get('delete/{id}', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'delete']);
    Route::get('STDRawMaterialList', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'STDRawMaterialList']);
    Route::post('filteredRawMaterial', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'STDRawMaterialList']);
    Route::get('STDRawMaterial/{id?}', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'AddSTDRawMaterial']);
    Route::Post('AddSTDRawMaterial', [App\Http\Controllers\QCSampleTesting\QCSampleTestingController::class, 'AddSTDRawMaterial']);
    Route::get('STDRowdelete/{id}', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'STDRowdelete']);
    Route::get('exportdata', [App\Http\Controllers\QCSampleTesting\QCSampleTestingViewController::class, 'ExportQCsample']);
    
});