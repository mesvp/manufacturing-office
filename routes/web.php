<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\OtherController;

require __DIR__ . '/adminauth.php';
require __DIR__ . '/FactoryCreate.php';
require __DIR__ . '/Dashboard.php';
require __DIR__ . '/GatePass.php';
require __DIR__ . '/Master.php';
require __DIR__ . '/MaterialManagement.php';
require __DIR__ . '/ProductCategories.php';
require __DIR__ . '/RawMaterial.php';
require __DIR__ . '/PPFinishedGood.php';
require __DIR__ . '/CertificateDetails.php';
require __DIR__ . '/QCSampleTesting.php';
require __DIR__ . '/SampleFreeGood.php';
require __DIR__ . '/BOM.php';
require __DIR__ . '/orderRequriement.php';
require __DIR__ . '/StoreRequistion.php';
require __DIR__ . '/Storeissue.php';
require __DIR__ . '/Production.php';
require __DIR__ . '/InventoryManagement.php';
require __DIR__ . '/Maintenance.php';
require __DIR__ . '/ProductionProcess.php';
require __DIR__ . '/ThirdParty.php';
require __DIR__ . '/Report.php';
require __DIR__ . '/SerialNumber.php';
require __DIR__ . '/FinishedGood.php';
require __DIR__ . '/StockTransfer.php';
require __DIR__ . '/ProductionLineUp.php';
require __DIR__ . '/ApprovalMatrix.php';




Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::get('login', function () {
    return view('auth.login');
})->name('login');
Route::group(['middleware' => 'auth:admin'], function () {
    Route::Post('CheckBoxStore', [OtherController::class, 'CheckBoxStore']);
    Route::get('getCheckBoxData', [OtherController::class, 'getCheckBoxData']);
});
