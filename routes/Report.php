<?php
Route::group(['prefix' => 'Report',  'middleware' => 'auth:admin'], function () {

    Route::get('storestockreport', [App\Http\Controllers\Report\ReportController::class, 'storestockreport']);
    Route::get('storestockreportdetails/{id?}', [App\Http\Controllers\Report\ReportController::class, 'storestockreportdetails']);
    Route::post('filtered', [App\Http\Controllers\Report\ReportController::class, 'storestockreport']);
    Route::post('filtered-details/{id?}', [App\Http\Controllers\Report\ReportController::class, 'storestockreportdetails']);
    Route::get('exportdata', [App\Http\Controllers\Report\ReportController::class, 'exportdata']);

    Route::get('plantstockreport', [App\Http\Controllers\Report\ReportController::class, 'plantstockreport']);
    Route::post('filtered_plantstock/{id?}', [App\Http\Controllers\Report\ReportController::class, 'plantstockreport']);
    Route::get('plantstockreportdetails/{id?}', [App\Http\Controllers\Report\ReportController::class, 'plantstockreportdetails']);
    Route::post('filtered-details-plant/{id?}', [App\Http\Controllers\Report\ReportController::class, 'plantstockreportdetails']);
    Route::get('exportdata_plant', [App\Http\Controllers\Report\ReportController::class, 'exportdata_plant']);
    
   // material stock report routes
    Route::any('sl_no_avlbl-report', [App\Http\Controllers\Report\SerialNumberController::class, 'list']);
    Route::get('exportdata_serial', [App\Http\Controllers\Report\SerialNumberController::class, 'exportdata']);
    Route::any('wip_sl_no_avlbl-report', [App\Http\Controllers\Report\SerialNumberController::class, 'wiplist']);
    Route::get('exportdata_wip_serial', [App\Http\Controllers\Report\SerialNumberController::class, 'exportdata_wip_serial']);
    Route::any('dis_sl_no_avlbl-report', [App\Http\Controllers\Report\SerialNumberController::class, 'dispatchlist']);
    Route::get('exportdispatchdata_serial', [App\Http\Controllers\Report\SerialNumberController::class, 'exportdispatchdata']);
    Route::any('material-stock-report', [App\Http\Controllers\Report\MaterialStockController::class, 'list']);
    Route::any('filtered_finishedgoodstock', [App\Http\Controllers\Report\MaterialStockController::class, 'list']);
    Route::get('ExportMaterialStockData',[App\Http\Controllers\Report\MaterialStockController::class,'ExportMaterialStock']);
    Route::get('MaterialStockDetail/{mat_id}', [App\Http\Controllers\Report\MaterialStockController::class, 'view']);
    Route::any('detailfiltered/{mat_id}', [App\Http\Controllers\Report\MaterialStockController::class, 'view']);
});
