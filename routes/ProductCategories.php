<?php

Route::group(['prefix' => 'ProductCategories',  'middleware' => 'auth:admin'], function () {
    Route::get('ProductList', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'ProductList']);
    Route::post('filtered', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'ProductList']);
    Route::get('AddProduct/{id?}', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'AddProduct']);
    Route::post('AddProduct', [App\Http\Controllers\ProductCategories\ProductCategoriesController::class, 'AddProduct']);
    Route::get('delete/{id}', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'delete']);
    Route::get('get-subproduct/{productid}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubproduct']);
    Route::get('get-subsubproduct/{subproductid}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubsubproduct']);
    Route::Post('CheckBoxStore', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'getCheckBoxData']);
    Route::get('ExportData', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'ExportData']);
    Route::get('ProductCategory_View/{id}/{type}', [App\Http\Controllers\ProductCategories\ProductCategoriesViewController::class, 'ProductCategory_View']);
    Route::get('ProductApproveList', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'ProductApprove']);
    Route::post('Filter-approve', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'ProductApprove']);
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'approve']);
    Route::get('Release_Hold/{id}', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'Release_Hold']);
    Route::get('CheckHoldExpiry', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\ProductCategories\ProductCategoriesApproveController::class, 'UpdateStatus']);
});
