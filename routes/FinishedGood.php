<?php
Route::group(['prefix' => 'FinishedGood',  'middleware' => 'auth:admin'], function () {

    ///////// FINISHED GOOD //////////////////////////////////
    Route::any('Finished_Good_List', [App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class, 'FinishedGood']);
    Route::post('AddFinishedGoodGatepass', [App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class, 'FinishedGood_store']);
    Route::post('updateFinishedGoodGatepass/{id}', [App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class, 'updateFinishedGoodGatepass']);
    Route::any('EditFinishedGoodGatepass/{id}', [App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class, 'EditFinishedGoodGatepass']);
    Route::get('ApproverView/{id}',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'approverview']);
    Route::get('FinishedGoodInputerView/{id}',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'view']);
    Route::any('Finished_Good_Approver_List',[App\Http\Controllers\FinishedGood\FinishedGoodApproverGatepassController::class,'FinishedGoodApproveList']);
    Route::get('ExportFinishedGoodData',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class,'ExportFinishedGood']);
    Route::get('ExportFinishedGoodViewData/{id}',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'ExportFinishedGoodView']);
    Route::post('trail',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'trail']);
    Route::post('action',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'action']);
    Route::post('inputeraction',[App\Http\Controllers\FinishedGood\FinishedGoodGatepassViewController::class,'inputeraction']);
    Route::post('approve',[App\Http\Controllers\FinishedGood\FinishedGoodApproverGatepassController::class,'approve']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\FinishedGood\FinishedGoodApproverGatepassController::class,'Release_Hold']);
    Route::post('check-serial-requirement', [App\Http\Controllers\FinishedGood\FinishedGoodGatepassController::class, 'checkSerialRequirement']);


});
