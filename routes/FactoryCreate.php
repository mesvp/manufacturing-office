<?php

Route::group(['prefix' => 'FactoryCreater',  'middleware' => 'auth:admin'], function () {
    Route::view('AddFactory', 'AddFactory');
    Route::get('Fectory_view/{id}/{type}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'Fectory_view']);
    Route::get('get-assetdeatsilsajax/{vid}',[App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getassetdeatsilsajax']);
    Route::get('List', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'Factory_Data']);
    Route::post('filtered', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'Factory_Data']);
    Route::get('/', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'Factory_Data']);
    Route::get('unset', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'unset']);
    Route::get('step1/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step1']);
    Route::post('address', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'address']);
    Route::get('step2/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step2']);
    Route::post('statutory', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'statutory']);
    Route::get('step3/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step3']);
    Route::post('Land_Building', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Land_Building']);
    Route::get('step4/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step4']);
    Route::post('Plant_Machinery', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Plant_Machinery']);
    Route::get('step5/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step5']);
    Route::post('Amenities', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Amenities']);
    Route::get('step6/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step6']);
    Route::post('Electricity', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Electricity']);
    Route::get('step7/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step7']);
    Route::post('WareHouse_Room', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'WareHouse_Room']);
    Route::get('step8/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step8']);
    Route::post('Office_Asset', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Office_Asset']);
    Route::get('step9/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step9']);
    Route::post('Power_House', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Power_House']);
    Route::get('step10/{id?}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'step10']);
    Route::post('Store', [App\Http\Controllers\FactoryCreater\FactoryCreaterController::class, 'Store']);
    Route::get('delete/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'destroy']);

    Route::get('get-states/{countryId}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getStates']);
    Route::get('get-cities/{stateId}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getCities']);

    Route::get('get-subproduct/{productid}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubproduct']);
    Route::get('get-subsubproduct/{subproductid}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getsubsubproduct']);

    Route::get('remove-file/{id}/{name}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'deletefile']);
    Route::get('remove-file_boundary/{id}/{name}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'deletefile_boundary']);


    Route::get('factory-approve', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'factory_approve']);
    Route::post('Filter-approve', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'factory_approve']);
    Route::get('view-approve/{id}/{type}', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'view_approve']);
    Route::post('approve', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'approve']);

    Route::Post('CheckBoxStore', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getCheckBoxData']);

    Route::get('ExportFilteredData', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'DownloadFilteredData']);

    Route::get('CheckHoldExpiry', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'CheckHoldExpiry']);
    Route::post('UpdateStatus', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'UpdateStatus']);

    Route::get('get-machinecode/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getmachinecode']);
    Route::get('get-accessories/{id}', [App\Http\Controllers\FactoryCreater\FactoryViewController::class, 'getaccessories']);

    Route::get('Release_Hold/{id}', [App\Http\Controllers\FactoryCreater\FactoryApproveController::class, 'Release_Hold']);
});
