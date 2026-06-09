<?php

Route::group(['prefix' => 'Storeissue',  'middleware' => 'auth:admin'], function () {
    Route::any('StoreissueList',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'StoreissueListpending']);
    Route::any('StoreissueListClosed',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'StoreissueListclose']);
    Route::any('StoreissueListDetails',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'StoreissueListDetails']);
    Route::get('release/{id?}/{idd?}',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'Release_Hold']);
    Route::post('Filter',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'filter_view']);
    Route::get('StoreissueApproveList',[App\Http\Controllers\Storeissue\StoreissueApproverViewController::class,'StoreissueList']);
    Route::post('filtered',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'StoreissueList']);
    Route::post('ForClose',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'forclose']);
    Route::post('IssueQty',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'issueqty']);
    Route::get('AddStoreissue/{id?}/{type?}',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'AddStoreissue']);
    Route::get('ViewStoreissue/{id?}/{type?}/{idd?}',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'ViewStoreissue']);
    Route::get('ViewStoreissueApprover/{id?}',[App\Http\Controllers\Storeissue\StoreissueApproverViewController::class,'ViewStoreissue']);
    Route::post('approve',[App\Http\Controllers\Storeissue\StoreissueApproverViewController::class,'Approve']);
    Route::post('inputapprove',[App\Http\Controllers\Storeissue\StoreissueApproverViewController::class,'Input_Approve']);
    Route::post('AddStoreissue',[App\Http\Controllers\Storeissue\StoreissueController::class,'AddStoreissue']);
    Route::get('delete/{id}',[App\Http\Controllers\Storeissue\StoreissueViewController::class,'delete']);   
    Route::get('get-store-stock/{id}', [App\Http\Controllers\Storeissue\StoreissueViewController::class, 'getstorestock']);
});
