<?php
Route::group(['prefix' => 'ThirdParty',  'middleware' => 'auth:admin'], function () {

    Route::get('ThirdPartyList', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'ThirdPartyList']);
    Route::post('filtered', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'ThirdPartyList']);
    Route::get('ThirdParty/{id?}', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'AddThirdParty']);
    Route::Post('AddThirdParty', [App\Http\Controllers\ThirdParty\ThirdPartyController::class, 'AddThirdParty']);
    Route::get('delete/{id}', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'delete']);
    Route::get('ThirdPartyApproveList', [App\Http\Controllers\ThirdParty\ThirdPartyApproveController::class, 'ThirdParty_approve']);
    Route::post('ApproveFilter', [App\Http\Controllers\ThirdParty\ThirdPartyApproveController::class, 'ThirdParty_approve']);
    Route::get('view-approve/{id}', [App\Http\Controllers\ThirdParty\ThirdPartyApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\ThirdParty\ThirdPartyApproveController::class, 'approve']);
    Route::get('FinishedGoodsList', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'FinishedGoodsList']);
    Route::post('filtered_FinishedGoods', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'FinishedGoodsList']);
    Route::get('FinishedGoods/{id?}', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'AddFinishedGoods']);
    Route::Post('AddFinishedGoods', [App\Http\Controllers\ThirdParty\ThirdPartyController::class, 'AddFinishedGoods']);
    Route::get('delete_FinishedGoods/{id}', [App\Http\Controllers\ThirdParty\ThirdPartyViewController::class, 'delete_FinishedGoods']);
});
