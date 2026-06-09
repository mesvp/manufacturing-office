<?php

Route::group(['prefix' => 'GatePass',  'middleware' => 'auth:admin'], function () {
    Route::get('List', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass_Data']);
    Route::get('employee-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass']);
    Route::post('filtered_Emp', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass_Data']);
    Route::post('employee-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Employee_Store']);
    Route::get('delete_Employee/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_Employee']);
    Route::get('visitor-list', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass_Data']);
    Route::post('filtered_Visitor', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass_Data']);
    Route::get('visitor-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass']);
    Route::get('visitors_details/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'visitors_details']);
    Route::post('visitor-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Visitor_store']);
    Route::get('delete_visitor/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_visitor']);
    Route::get('material-list', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass_Data']);
    Route::post('filtered_material', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass_Data']);
    Route::get('material-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass']);
    Route::post('material-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Material_Store']);
    Route::get('delete_material/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_material']);
    Route::get('delete_visitors/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_visitors']);

    Route::Post('CheckBoxStore', [App\Http\Controllers\GatePass\GatePassViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getCheckBoxData']);

    Route::get('ExportEmployee', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportEmployee']);
    Route::get('ExportVisitor', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportVisitor']);
    Route::get('ExportMaterial', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportMaterial']);

    Route::get('employee_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'employee_view']);
    Route::get('visitors_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'visitors_view']);
    Route::get('Material_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_view']);
    Route::get('Material_out_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_out_view']);
    Route::get('download-gatepass', [App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadGatepass']);
    Route::get('/downloadPDF/{id}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadPDF']);
    Route::get('/downloadoutPDF/{id}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadoutPDF']);
    Route::post('/uploadCopy/{id}',[App\Http\Controllers\GatePass\GatePassController::class, 'uploadCopy']);
    Route::post('/uploadoutCopy/{id}',[App\Http\Controllers\GatePass\GatePassController::class, 'uploadoutCopy']);
    Route::get('download-hardcopy', [App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadHardcopy']);
});
