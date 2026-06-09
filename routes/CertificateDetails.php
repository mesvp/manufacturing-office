<?php

Route::group(['prefix' => 'CertificateDetails',  'middleware' => 'auth:admin'], function () {

    Route::get('CertificateDetailsList', [App\Http\Controllers\CertificateDetails\CertificateDetailsViewController::class, 'CertificateDetailsList']);
    Route::post('filtered', [App\Http\Controllers\CertificateDetails\CertificateDetailsViewController::class, 'CertificateDetailsList']);
    Route::get('CertificateDetails/{id?}', [App\Http\Controllers\CertificateDetails\CertificateDetailsViewController::class, 'AddCertificateDetails']);
    Route::Post('AddCertificateDetails', [App\Http\Controllers\CertificateDetails\CertificateDetailsController::class, 'AddCertificateDetails']);
    Route::get('delete/{id}', [App\Http\Controllers\CertificateDetails\CertificateDetailsViewController::class, 'delete']);
    Route::get('remove-file/{id}', [App\Http\Controllers\CertificateDetails\CertificateDetailsViewController::class, 'DeleteAttachment']);
    Route::get('CertificateDetailsApproveList', [App\Http\Controllers\CertificateDetails\CertificateDetailsApproveController::class, 'CertificateDetails_approve']);

});
