<?php

Route::group(['prefix' => 'GatePass',  'middleware' => 'auth:admin'], function () {
    Route::get('List', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass_Data']);

    // for in approvals
    Route::any('Employee_Gatepass_Approval',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalController::class,'EmployeeGatepassApproveList']);
    Route::post('filtered_ApproveEmp', [App\Http\Controllers\GatePass\EmployeeGatePassApprovalController::class,'EmployeeGatepassApproveList']);

    Route::post('employee_approve',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalController::class,'approve']);
    Route::get('Release_Hold/{id?}',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalController::class,'Release_Hold']);
    Route::get('EditEmployeeGatepass/{id?}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'EditEmployeeGatepass']);
    Route::post('updateEmployeeGatepass/{id}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'updateEmployeeGatepass']);

    // for out approvals
    Route::post('filtered_ApproveOutEmp', [App\Http\Controllers\GatePass\EmployeeGatePassApprovalOutController::class,'EmployeeGatepassOut_ApproveList']);
    Route::any('Employee_Gatepass_Out_Approval',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalOutController::class,'EmployeeGatepassOut_ApproveList']);
    Route::post('out_employee_approve',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalOutController::class,'Out_approve']);
    Route::get('Out_Release_Hold/{id?}',[App\Http\Controllers\GatePass\EmployeeGatePassApprovalOutController::class,'Out_Release_Hold']);

    Route::get('employee_gatepass_approval_view/{id?}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'approverview']);
    Route::post('employee_trail',[App\Http\Controllers\GatePass\GatePassViewController::class,'trail']);
    Route::post('employee_action',[App\Http\Controllers\GatePass\GatePassViewController::class,'action']);
    Route::post('employee_inputeraction',[App\Http\Controllers\GatePass\GatePassViewController::class,'inputeraction']);
    
    Route::get('employee-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass']);
    Route::post('filtered_Emp', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Employee_Gatepass_Data']);
    Route::post('employee-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Employee_Store']);
    Route::get('delete_Employee/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_Employee']);
    
    Route::get('/downloadEmployeePDF/{id}/{type}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadEmployeePDF']);

    

    
    Route::get('get-employee-details/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getEmployeeDetails']);

    Route::get('visitor-list', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass_Data']);
    Route::post('filtered_Visitor', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass_Data']);
    Route::get('visitor-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass']);
    Route::get('visitors_details/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'visitors_details']);
    Route::post('visitor-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Visitor_store']);
    Route::get('delete_visitor/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_visitor']);

    // for in approvals
    Route::any('Visitor_Gatepass_Approval',[App\Http\Controllers\GatePass\VisitorGatePassApprovalController::class,'VisitorGatepassApproveList']);
    Route::post('filtered_ApproveVisitor', [App\Http\Controllers\GatePass\VisitorGatePassApprovalController::class,'VisitorGatepassApproveList']);

    Route::post('visitor_approve',[App\Http\Controllers\GatePass\VisitorGatePassApprovalController::class,'approve']);
    //Route::get('Release_Hold/{id?}',[App\Http\Controllers\GatePass\VisitorGatePassApprovalController::class,'Release_Hold']);
    Route::get('Visitor_Release_Hold/{id?}',[App\Http\Controllers\GatePass\VisitorGatePassApprovalController::class,'Release_Hold']);
    Route::get('EditVisitorGatepass/{id?}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'EditVisitorGatepass']);
    Route::post('updateVisitorGatepass/{id}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'updateVisitorGatepass']);

    // for out approvals
    Route::post('filtered_ApproveOutVisitor', [App\Http\Controllers\GatePass\VisitorGatePassApprovalOutController::class,'VisitorGatepassOut_ApproveList']);
    Route::any('Visitor_Gatepass_Out_Approval',[App\Http\Controllers\GatePass\VisitorGatePassApprovalOutController::class,'VisitorGatepassOut_ApproveList']);
    Route::post('out_visitor_approve',[App\Http\Controllers\GatePass\VisitorGatePassApprovalOutController::class,'Out_approve']);
    //Route::get('Out_Release_Hold/{id?}',[App\Http\Controllers\GatePass\VisitorGatePassApprovalOutController::class,'Out_Release_Hold']);
    Route::get('Visitor_Out_Release_Hold/{id?}',[App\Http\Controllers\GatePass\VisitorGatePassApprovalOutController::class,'Out_Release_Hold']);

    Route::get('visitor_gatepass_approval_view/{id?}/{type?}',[App\Http\Controllers\GatePass\GatePassViewController::class,'visitorapproverview']);
    Route::post('visitor_trail',[App\Http\Controllers\GatePass\GatePassViewController::class,'visitor_trail']);
    Route::post('visitor_action',[App\Http\Controllers\GatePass\GatePassViewController::class,'visitor_action']);
    Route::post('visitor_inputeraction',[App\Http\Controllers\GatePass\GatePassViewController::class,'visitor_inputeraction']);
    
    Route::get('visitor-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Visitor_Gatepass']);
    Route::post('visitor-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Visitor_Store']);
    Route::get('delete_Visitor/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_Visitor']);
    
    Route::get('/downloadVisitorPDF/{id}/{type}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadVisitorPDF']);





    Route::get('material-list', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass_Data']);
    Route::get('filtered_material', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass_Data']);
    Route::get('material-gatepass/{id?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_Gatepass']);
    Route::post('material-store', [App\Http\Controllers\GatePass\GatePassController::class, 'Material_Store']);
    Route::get('delete_material/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_material']);
    Route::get('delete_visitors/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'delete_visitors']);

    Route::Post('CheckBoxStore', [App\Http\Controllers\GatePass\GatePassViewController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getCheckBoxData']);

    Route::get('ExportVisitor', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportVisitor']);
    Route::get('ExportEmployee', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportEmployee']);
    Route::get('ExportMaterial', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportMaterial']);

    Route::get('employee_view/{id}/{type?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'employee_view']);
    Route::get('visitors_view/{id}/{type?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'visitors_view']);
    Route::get('Material_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_view']);
    Route::get('Material_out_view/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_out_view']);
    Route::get('download-gatepass', [App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadGatepass']);
    Route::get('/downloadPDF/{id}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadPDF']);
    Route::get('/downloadoutPDF/{id}',[App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadoutPDF']);
    Route::post('/uploadCopy/{id}',[App\Http\Controllers\GatePass\GatePassController::class, 'uploadCopy']);
    Route::post('/uploadoutCopy/{id}',[App\Http\Controllers\GatePass\GatePassController::class, 'uploadoutCopy']);
    Route::get('download-hardcopy', [App\Http\Controllers\GatePass\GatePassViewController::class, 'downloadHardcopy']);
    Route::get('ExportMaterialSlno/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'ExportMaterialSlno']);
    
    Route::post('/fetch-Material/{materialId?}',[App\Http\Controllers\GatePass\GatePassController::class, 'getMaterial']);
    Route::get('get-plantnamedetails/{id}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getplantnamedetails']);

    
    //Dt.04-12-25
    Route::get('get-invoice-dtls', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getInvoiceDtls']);
    Route::get('get-invoice-dtls-in', [App\Http\Controllers\GatePass\GatePassViewController::class, 'getInvoiceDtlsIn']);
    
    
    // for in approvals
    Route::any('Material_Gatepass_Approval',[App\Http\Controllers\GatePass\MaterialGatePassApprovalController::class,'MaterialGatepassApproveList']);
    Route::post('filtered_ApproveMaterial', [App\Http\Controllers\GatePass\MaterialGatePassApprovalController::class,'MaterialGatepassApproveList']);
    Route::post('material_approve',[App\Http\Controllers\GatePass\MaterialGatePassApprovalController::class,'approve']);
    Route::get('Material_Release_Hold/{id?}',[App\Http\Controllers\GatePass\MaterialGatePassApprovalController::class,'Release_Hold']); 
    Route::post('material_inputeraction',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_inputeraction']);
    Route::post('material_trail',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_trail']);
    Route::post('material_action',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_action']);
    Route::get('Material_view/{id}/{type?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_view']);
    
    // for out approvals
    Route::any('Material_Gatepass_Out_Approval',[App\Http\Controllers\GatePass\MaterialGatePassApprovalOutController::class,'MaterialGatepassOut_ApproveList']);
    Route::post('filtered_ApproveMaterial_Out', [App\Http\Controllers\GatePass\MaterialGatePassApprovalOutController::class,'MaterialGatepassOut_ApproveList']);
    Route::post('material_out_approve',[App\Http\Controllers\GatePass\MaterialGatePassApprovalOutController::class,'Out_approve']);
    Route::get('Material_Out_Release_Hold/{id?}',[App\Http\Controllers\GatePass\MaterialGatePassApprovalOutController::class,'Out_Release_Hold']);
    Route::post('material_out_inputeraction',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_out_inputeraction']);
    Route::post('material_out_trail',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_out_trail']);
    Route::post('material_out_action',[App\Http\Controllers\GatePass\GatePassViewController::class,'material_out_action']);
    Route::get('Material_out_view/{id}/{type?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'Material_out_view']);

    Route::get('EditMaterialGatepass/{id}/{type?}', [App\Http\Controllers\GatePass\GatePassViewController::class, 'EditMaterialGatepass']);
    Route::post('material-update/{id}', [App\Http\Controllers\GatePass\GatePassController::class, 'material_update']);

});