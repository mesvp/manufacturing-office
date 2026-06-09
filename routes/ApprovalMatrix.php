<?php
Route::group(['prefix' => 'approval-matrix',  'middleware' => 'auth:admin'], function () {
  Route::any('approval-module',[App\Http\Controllers\ApprovalMatrix\ApprovalModule_Controller::class,'index']);
  Route::post('approval-module/insert',[App\Http\Controllers\ApprovalMatrix\ApprovalModule_Controller::class,'insert']);
  Route::any('approval-master',[App\Http\Controllers\ApprovalMatrix\Approvar_Controller::class,'index']);
  Route::any('approval-master/insertApprover',[App\Http\Controllers\ApprovalMatrix\Approvar_Controller::class,'insert']);
});