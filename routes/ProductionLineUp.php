<?php

Route::group(['prefix' => 'production-lineup',  'middleware' => 'auth:admin'], function () {
    
    Route::any('dashboard', [App\Http\Controllers\ProductionLineUp\Dashboard_Controller::class, 'index']);
    Route::any('dashboard/daily-production', [App\Http\Controllers\ProductionLineUp\DashboardReports\DailyProduction::class, 'index']);
	Route::any('dashboard/rejection-percentage', [App\Http\Controllers\ProductionLineUp\DashboardReports\RejectionPercentage::class, 'index']);
	

	Route::any('material', [App\Http\Controllers\ProductionLineUp\Master\Material_Controller::class, 'index']);
	Route::post('material/insert', [App\Http\Controllers\ProductionLineUp\Master\Material_Controller::class, 'insert']);
	

	Route::any('production-setup', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'index']);
	Route::any('production-setup-all', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'ProductionAll']);
	Route::any('production-setup/approval-list', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'Approval_list']);
	Route::any('production-setup/add-production', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'addProduction']);
	Route::any('production-setup/add-production/get-material-uom/{id}', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'getMatUOMAjax']);
	Route::post('production-setup/add-production/insert', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'insert']);
	Route::get('production-setup/view-details/{value}', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'viewDetails']);
	Route::get('production-setup/approve/{value}', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'approveDtls']);
	Route::post('production-setup/approvalAction', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'approvalAction']);
    Route::any('production-setup-form-update/{batchNo}', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'formUpdateView'])->name('production-setup-form-update');
	Route::post('production-setup/update/{batchNo}', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'updateProduction'])->name('production-setup-update');

    Route::get('production-setup/ajaxBomMat', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'getFinishedGoodDataAjax']);
    Route::get('production-setup/ajaxBomMatQty', [App\Http\Controllers\ProductionLineUp\ProductionSetUp\ProductionSetUp_Controller::class, 'getFinishedGoodDataAjax2']);


	Route::any('cell-cutting', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'index']);
	Route::any('cell-cutting-all', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'cellCuttingAll']);
	Route::any('cell-cutting-approval-list', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'Approval_list']);
	Route::any('cell-cutting-detailed', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'Detailed_list']);
	Route::any('cell-cutting-add', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'add_cell_cutting'])->name('cell-cutting-add');
	Route::any('cell-cutting-view/{id?}', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'view_cell_cutting'])->name('cell-cutting-view');
	Route::post('cell-cutting-store', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'store_cell_cutting'])->name('cell-cutting-store');
	Route::get('cell-cutting/approve/{value}', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'approveDtls']);
	Route::post('cell-cutting/approvalAction', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'approvalAction']);
	Route::get('cell-cutting/getCellCuttingMaterial', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'getCellCuttingMaterial']);
    Route::any('cell-cutting-form-update/{id}', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'formUpdateView'])->name('cell-cutting-form-update');
	Route::post('cell-cutting/update/{id}', [App\Http\Controllers\ProductionLineUp\CellCutting\CellCutting_Controller::class, 'updateCellCutting'])->name('cell-cutting.update');


	Route::any('stringer-op', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'index']);
	Route::any('stringer-op-all', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'StringerOPAll']);
	Route::any('stringer-op-approval-list', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'Approval_list']);
	Route::any('stringer-op-detailed', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'Detailed_list']);
	Route::any('stringer-op-add', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'add_stringer_op'])->name('stringer-op-add');
	Route::any('stringer-op-view/{id?}', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'view_stringer_op'])->name('stringer-op-view');
	Route::post('stringer-op-store', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'store_stringer_op'])->name('stringer-op-store');
	Route::get('stringer-op/approve/{value}', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'approveDtls']);
	Route::post('stringer-op/approvalAction', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'approvalAction']);
	Route::get('stringer-op/getstringerOPMaterial', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'getstringerOPMaterial']);
    Route::any('stringer-op-form-update/{id}', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'formUpdateView'])->name('stringer-op-form-update');
	Route::post('stringer-op/update/{id}', [App\Http\Controllers\ProductionLineUp\StringerOP\StringerOP_Controller::class, 'updateStringerOP'])->name('stringer-op-update');


	Route::any('stringer-qc', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'index']);
	Route::any('stringer-qc-all', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'StringerQCAll']);
	Route::any('stringer-qc-approval-list', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'Approval_list']);
	Route::any('stringer-qc-detailed', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'Detailed_list']);
	Route::any('stringer-qc-add', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'add_stringer_qc'])->name('stringer-qc-add');
	Route::any('stringer-qc-view/{id?}', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'view_stringer_qc'])->name('stringer-qc-view');
	Route::post('stringer-qc-store', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'store_stringer_qc'])->name('stringer-qc-store');
	Route::get('stringer-qc/approve/{value}', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'approveDtls']);
	Route::post('stringer-qc/approvalAction', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'approvalAction']);
	Route::get('stringer-qc/getstringerQCMaterial', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'getstringerQCMaterial']);
    Route::any('stringer-qc-form-update/{id}', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'formUpdateView'])->name('stringer-qc-form-update');
	Route::post('stringer-qc/update/{id}', [App\Http\Controllers\ProductionLineUp\StringerQC\StringerQC_Controller::class, 'updateStringerQC'])->name('stringer-qc-update');


	Route::any('stringer-rework', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'index']);
	Route::any('stringer-rework-all', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'StringerRWAll']);
	Route::any('stringer-rework-approval-list', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'Approval_list']);
	Route::any('stringer-rework-detailed', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'Detailed_list']);
	Route::any('stringer-rework-add', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'add_stringer_rework'])->name('stringer-rework-add');
	Route::any('stringer-rework-view/{id?}', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'view_stringer_rework'])->name('stringer-rework-view');
	Route::post('stringer-rework-store', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'store_stringer_rework'])->name('stringer-rework-store');
	Route::get('stringer-rework/approve/{value}', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'approveDtls']);
	Route::post('stringer-rework/approvalAction', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'approvalAction']);
	Route::get('stringer-rework/getstringerReWorkMaterial', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'getstringerReWorkMaterial']);
    Route::any('stringer-rework-form-update/{id}', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'formUpdateView'])->name('stringer-rework-form-update');
	Route::post('stringer-rework/update/{id}', [App\Http\Controllers\ProductionLineUp\StringerReWork\StringerReWork_Controller::class, 'updateStringerRework'])->name('stringer-rework-update');

	Route::any('glass-feeding', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'index']);
	Route::any('glass-feeding-all', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'GlassFeedingAll']);
	Route::any('glass-feeding-approval-list', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'Approval_list']);
	Route::any('glass-feeding-detailed', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'Detailed_list']);
	Route::any('glass-feeding-add', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'add_glass_feeding'])->name('glass-feeding-add');
	Route::any('glass-feeding-view/{id?}', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'view_glass_feeding'])->name('glass-feeding-view');
	Route::get('glass-feeding-add/getGlassMaterial', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'getGlassMaterial']);
	Route::any('glass-feeding-add/insert', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'insert']);
	Route::get('glass-feeding/approve/{value}', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'approveDtls']);
	Route::post('glass-feeding/approvalAction', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'approvalAction']);
    Route::any('glass-feeding-form-update/{id}', [App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'formUpdateView'])->name('glass-feeding-form-update');
	Route::post('glass-feeding-update/{id}',[App\Http\Controllers\ProductionLineUp\GlassFeeding\GlassFeeding_Controller::class, 'updateGlassFeeding'])->name('glass-feeding-update');
	
	Route::any('bushing-setup', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'index']);
	
	Route::any('bushing-setup/all-list', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'allList']);
	
	Route::any('bushing-setup/all-list-excel', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'allExcelDownload']);

    Route::any('bushing-setup/add', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'add_bushing_setup'])->name('bushing-setup-add');
	Route::any('bushing-setup/insert', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'insert']);
	Route::any('bushing-setup/view/{id?}', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'view_bushing_setup'])->name('bushing-setup-view');
	Route::get('bushing-setup/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'getBushingMaterial']);
	Route::any('bushing-setup/bushing-details', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'bushing_details']);
	Route::any('bushing-setup/bushing-damage-report', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'bushing_damage_report']);
	Route::get('bushing-setup/getFinishedGoodData', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'getFinishedGoodData']);
	Route::get('bushing-setup/validate-rfid', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'validateRFID']);
	Route::get('bushing-setup/validate-barcode', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'validateBarCode']);
	Route::get('bushing-setup/getUOM', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'getUOM']);
	Route::get('bushing-setup/ExportBushMaterial', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'exportBushMaterial']);
	Route::get('bushing-setup/ExportBushMaterial2', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'exportBushMaterial2']);
	Route::get('bushing-setup/pdfBushMaterial', [App\Http\Controllers\ProductionLineUp\BushingSetup\BushingSetup_Controller::class, 'pdfBushMaterial']);


    Route::get('el_qc', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'index']);
	Route::get('el_qc-passed', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcPassed']);
	Route::get('el_qc-rejected', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcRejected']);

    Route::get('el_qc-all', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcAll']);  
	Route::get('el_qc-all-passed', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcAllPassed']);
	Route::get('el_qc-all-rejected', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcAllRejected']);
	
    Route::get('el_qc-pending-excel-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'pendingExcel']);
    Route::get('el_qc-pending-pdf-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'pendingPDF']);
    
    Route::get('el_qc-passed-excel-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'passedExcel']);
    Route::get('el_qc-passed-pdf-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'passedPDF']);
    
    Route::get('el_qc-rejected-excel-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'rejectedExcel']);
    Route::get('el_qc-rejected-pdf-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'rejectedPDF']);
    
    Route::get('el_qc_rework-excel-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcReworkExcel']);
	Route::get('el_qc_damage-excel-export', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'elqcDamageExcel']);
	
	
	Route::any('el-qc-add', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'add_el_qc'])->name('el-qc-add');
	Route::any('el-qc-view/{id?}/{page?}', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'view_el_qc'])->name('el-qc-view');
	Route::post('el-qc-store', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'store_el_qc'])->name('el-qc-store');
	Route::get('el-qc/getBushingId', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'getBushingId']);
	Route::get('el-qc/getDefBatchId', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'getDefBatchId']);
	Route::get('el-qc/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'getBushingMaterial']);
	Route::get('el-qc/validate-rfid', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'validateRFID']);
	Route::get('el-qc/validate-barcode', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'validateBarCode']);
	Route::get('el_qc_rework', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'el_qc_rework']);
	Route::post('el-qc/update/{id}', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'update_el_qc'])->name('el-qc-update');
	Route::get('el-qc/el_qc_damage', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'el_qc_damage']);
	Route::get('el-qc/fetchRFIDBar', [App\Http\Controllers\ProductionLineUp\ElQC\ElQC_Controller::class, 'fetchRFIDBar']);
	

	Route::any('laminator-op', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'index']);
	Route::any('laminator-op/laminator-damage-report', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'laminator_damage_report']);
	Route::any('laminator-op-add', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'add_laminator_op'])->name('laminator-op-add');
	Route::any('laminator-op-store', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'insert']);
	Route::any('laminator-op/view/{id?}', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'view_laminator_op'])->name('laminator-op-view');
	Route::get('laminator-op/getFinishedGoodData', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'getFinishedGoodData']);
	Route::get('laminator-op/getelqcId', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'getelqcId']);
	Route::get('laminator-op/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'getBushingMaterial']);
	Route::get('laminator-op/checkCycleSlno', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'checkCycleSlno']);
	Route::any('laminator-op-rework', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'laminator_op_rework']);
	Route::any('laminator-op-view/{id?}/{page?}', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'view_laminator_op'])->name('laminator-op-view');
	Route::post('laminator-op/update/{id}', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'update_laminator_op'])->name('laminator-op-update');
	Route::get('laminator-op/validate-rfid', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'validateRFID']);
	Route::get('laminator-op/validate-barcode', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'validateBarCode']);
	Route::get('laminator-op/fetchRFIDBar', [App\Http\Controllers\ProductionLineUp\LaminatorOP\LaminatorOP_Controller::class, 'fetchRFIDBar']);


     Route::any('90deg-qc', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'index']);
    Route::any('90deg-qc-passed', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'passedList']);
    Route::any('90deg-qc-rejected', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'rejectedList']);

    
    Route::any('90deg-qc-all', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'indexAll']);
    Route::any('90deg-qc-passed-all', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'passedListAll']);
    Route::any('90deg-qc-rejected-all', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'rejectedListAll']);

    
    Route::any('90deg-qc-excel', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'pendingExcel']);
    Route::any('90deg-qc-passed-excel', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'passedExcel']);
    Route::any('90deg-qc-rejected-excel', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'rejectedExcel']);
    
    
    Route::any('90deg-qc/damage-report', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'damage_report']);
    Route::any('90deg-qc/add', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'add_qc'])->name('add_qc');
    Route::get('90deg-qc/getelaminatorId', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'getLaminatorId']);
    Route::get('90deg-qc/validate-rfid', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'validateRFID']);
    Route::get('90deg-qc/validate-barcode', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'validateBarCode']);
    Route::any('90deg-qc/insert', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'insert']);
    Route::any('90deg-qc-view/{id?}', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'view_90deg_qc'])->name('90deg-qc-view');
    Route::get('90deg-qc/getLaminatorMaterial', [App\Http\Controllers\ProductionLineUp\Trimming\Trimming_Controller::class, 'getLaminatorMaterial']);
    Route::get('90deg-qc/getFinishedGoodData', [App\Http\Controllers\ProductionLineUp\Trimming\Trimming_Controller::class, 'getFinishedGoodData']);
    Route::get('90deg-qc/getelqcId', [App\Http\Controllers\ProductionLineUp\Trimming\Trimming_Controller::class, 'getelqcId']);
    
    Route::get('90deg-qc/validate-barcode', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'validateBarCode']);
    Route::get('90deg-qc/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'getBushingMaterial']);
    
    Route::get('90deg-qc/fetchRFIDBar', [App\Http\Controllers\ProductionLineUp\NinetyDegQC\NinetyDeg_Controller::class, 'fetchRFIDBar']);


    Route::any('dlamination', [App\Http\Controllers\ProductionLineUp\DLamination\DLamination_Controller::class, 'index']);
    Route::any('dlamination/view/{id?}', [App\Http\Controllers\ProductionLineUp\DLamination\DLamination_Controller::class, 'view_delamination'])->name('delamination-view');
	Route::post('dlamination/update/{id}', [App\Http\Controllers\ProductionLineUp\DLamination\DLamination_Controller::class, 'update_delamination'])->name('delamination-update');
    Route::any('dlamination/damage-report', [App\Http\Controllers\ProductionLineUp\DLamination\DLamination_Controller::class, 'damage_report']);
    
    Route::any('junctionbox', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'index']);
    Route::any('junctionbox/passed', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'passed']);
    Route::any('junctionbox/rejected', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'rejected']);
    
    Route::any('junctionbox/pending-excel', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'pendingExcel']);
    Route::any('junctionbox/passed-excel', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'passedExcel']);
    Route::any('junctionbox/rejected-excel', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'rejectedExcel']);
    
    
    Route::any('junctionbox-all', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'indexAll']);
    
    
    Route::any('junctionbox/add', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'addJunctionBox'])->name('add-junctionbox');
    Route::get('junctionbox/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'getBushingMaterial']);
    Route::get('junctionbox/getQCId', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'getQCId']);
    Route::get('junctionbox/validate-rfid', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'validateRFID']);
    Route::get('junctionbox/validate-barcode', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'validateBarCode']);
    Route::any('junctionbox/insert', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'insert']);
    Route::any('junctionbox/view/{id?}', [App\Http\Controllers\ProductionLineUp\JunctionBox\JunctionBox_Controller::class, 'view_jb'])->name('jb-view');
    
    
    Route::any('final-qc', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'index']);
    Route::any('final-qc/passed', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'passedList']);
    Route::any('final-qc/rejected', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'rejectedList']);
        
    Route::any('final-qc-excel', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'pendingExcel']);
    Route::any('final-qc-passed-excel', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'passedExcel']);
    Route::any('final-qc-rejected-excel', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'rejectedExcel']);
	
    Route::any('final-qc-all', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'indexAll']);
    
    
    Route::any('final-qc/add', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'add'])->name('add-fqc');
    Route::get('final-qc/getBushingMaterial', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'getBushingMaterial']);
    Route::get('final-qc/getQCId', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'getJBId']);
    Route::get('final-qc/validate-rfid', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'validateRFID']);
    Route::get('final-qc/validate-barcode', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'validateBarCode']);
    Route::any('final-qc/insert', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'insert']);
    Route::any('final-qc/view/{id?}', [App\Http\Controllers\ProductionLineUp\FinalQC\FinalQC_Controller::class, 'view_fqc'])->name('fqc-view');
    
    
	
    Route::any('pallete', [App\Http\Controllers\ProductionLineUp\Pallete\Pallete_Controller::class, 'index']);
    Route::any('pallete/insert', [App\Http\Controllers\ProductionLineUp\Pallete\Pallete_Controller::class, 'insert']);
  
  
  
  //RAW Material Report
  Route::any('raw-material-report/pending-raw-material-elqc', [App\Http\Controllers\ProductionLineUp\RawMaterial\ElQC_Controller::class, 'availRawMat']);
  Route::any('raw-material-report/pending-raw-material-elqc/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\ElQC_Controller::class, 'availRawMatDtls']);
  Route::any('raw-material-report/consumed-raw-material-elqc', [App\Http\Controllers\ProductionLineUp\RawMaterial\ElQC_Controller::class, 'consumeRawMat']);
  Route::any('raw-material-report/consumed-raw-material-elqc/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\ElQC_Controller::class, 'consumeRawMatDtls']);
  
  Route::any('raw-material-report/pending-raw-material-90deg', [App\Http\Controllers\ProductionLineUp\RawMaterial\Ninetydeg_Controller::class, 'availRawMat']);
  Route::any('raw-material-report/pending-raw-material-90deg/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\Ninetydeg_Controller::class, 'availRawMatDtls']);
  Route::any('raw-material-report/consumed-raw-material-90deg', [App\Http\Controllers\ProductionLineUp\RawMaterial\Ninetydeg_Controller::class, 'consumeRawMat']);
  Route::any('raw-material-report/consumed-raw-material-90deg/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\Ninetydeg_Controller::class, 'consumeRawMatDtls']);
  
  Route::any('raw-material-report/pending-raw-material-jb', [App\Http\Controllers\ProductionLineUp\RawMaterial\JB_Controller::class, 'availRawMat']);
  Route::any('raw-material-report/pending-raw-material-jb/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\JB_Controller::class, 'availRawMatDtls']);
  Route::any('raw-material-report/consumed-raw-material-jb', [App\Http\Controllers\ProductionLineUp\RawMaterial\JB_Controller::class, 'consumeRawMat']);
  Route::any('raw-material-report/consumed-raw-material-jb/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\JB_Controller::class, 'consumeRawMatDtls']);
  
  Route::any('raw-material-report/pending-raw-material-fqc', [App\Http\Controllers\ProductionLineUp\RawMaterial\FQC_Controller::class, 'availRawMat']);
  Route::any('raw-material-report/pending-raw-material-fqc/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\FQC_Controller::class, 'availRawMatDtls']);
  Route::any('raw-material-report/consumed-raw-material-fqc', [App\Http\Controllers\ProductionLineUp\RawMaterial\FQC_Controller::class, 'consumeRawMat']);
  Route::any('raw-material-report/consumed-raw-material-fqc/view-details', [App\Http\Controllers\ProductionLineUp\RawMaterial\FQC_Controller::class, 'consumeRawMatDtls']);
  

  //Master-> Plant Capacity
  Route::any('master/plant_capacity', [App\Http\Controllers\ProductionLineUp\Master\PlantCapacity_Master::class, 'index']);
	Route::post('master/plant_capacity/insert', [App\Http\Controllers\ProductionLineUp\Master\PlantCapacity_Master::class, 'insert']);
	Route::post('master/plant_capacity/approvalAction', [App\Http\Controllers\ProductionLineUp\Master\PlantCapacity_Master::class, 'approvalAction']);
   
  //Master-> Plant Target
  Route::any('master/plant_target', [App\Http\Controllers\ProductionLineUp\Master\PlantTarget_Master::class, 'index']);
	Route::post('master/plant_target/insert', [App\Http\Controllers\ProductionLineUp\Master\PlantTarget_Master::class, 'insert']);
	Route::post('master/plant_target/approvalAction', [App\Http\Controllers\ProductionLineUp\Master\PlantTarget_Master::class, 'approvalAction']);
   
  //Master-> Shift Timing
  Route::any('master/shift_timing', [App\Http\Controllers\ProductionLineUp\Master\ShiftTiming_Master::class, 'index']);
	Route::post('master/shift_timing/insert', [App\Http\Controllers\ProductionLineUp\Master\ShiftTiming_Master::class, 'insert']);
	Route::post('master/shift_timing/approvalAction', [App\Http\Controllers\ProductionLineUp\Master\ShiftTiming_Master::class, 'approvalAction']);
   
});












