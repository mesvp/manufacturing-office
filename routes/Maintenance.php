<?php

Route::group(['prefix' => 'Maintenance',  'middleware' => 'auth:admin'], function () {
    Route::get('AssignList',[App\Http\Controllers\Maintenance\AssignViewController::class,'AssignList']);
    Route::post('filtered',[App\Http\Controllers\Maintenance\AssignViewController::class,'AssignList']);
    Route::get('Assign/{id?}',[App\Http\Controllers\Maintenance\AssignViewController::class,'Assign']);
    Route::post('AddAssign',[App\Http\Controllers\Maintenance\AssignController::class,'AddAssign']);
    Route::get('delete/{id}',[App\Http\Controllers\Maintenance\AssignViewController::class,'delete']);
    Route::get('MachineShutdownDetailsList',[App\Http\Controllers\Maintenance\MachineShutdownViewController::class,'MachineList']);
    Route::post('filtered_Machine',[App\Http\Controllers\Maintenance\MachineShutdownViewController::class,'MachineList']);
    Route::get('MachineShutdownDetails/{id?}',[App\Http\Controllers\Maintenance\MachineShutdownViewController::class,'AddMachine']);
    Route::post('AddMachineShutdownDetails',[App\Http\Controllers\Maintenance\MachineShutdownController::class,'AddMachineShutdown']);
    Route::get('delete_Machine/{id}',[App\Http\Controllers\Maintenance\MachineShutdownViewController::class,'delete_Machine']);
    Route::get('BreakdownList',[App\Http\Controllers\Maintenance\BreakdownViewController::class,'BreakdownList']);
    Route::post('filtered_Breakdown',[App\Http\Controllers\Maintenance\BreakdownViewController::class,'BreakdownList']);
    Route::get('Breakdown/{id?}',[App\Http\Controllers\Maintenance\BreakdownViewController::class,'Breakdown']);
    Route::post('AddBreakdown',[App\Http\Controllers\Maintenance\BreakdownController::class,'AddBreakdown']);
    Route::get('delete_Breakdown/{id}',[App\Http\Controllers\Maintenance\BreakdownViewController::class,'delete_Breakdown']);
    Route::get('MaintenanceList',[App\Http\Controllers\Maintenance\MaintenanceViewController::class,'MaintenanceList']);
    Route::post('filtered_Maintenance',[App\Http\Controllers\Maintenance\MaintenanceViewController::class,'MaintenanceList']);
    Route::get('Maintenance/{id?}',[App\Http\Controllers\Maintenance\MaintenanceViewController::class,'Maintenance']);
    Route::post('AddMaintenance',[App\Http\Controllers\Maintenance\MaintenanceController::class,'AddMaintenance']);
    Route::get('delete_Maintenance/{id}',[App\Http\Controllers\Maintenance\MaintenanceViewController::class,'delete_Maintenance']);

});