<?php

use App\Http\Controllers\AddAgreementController;
use App\Http\Controllers\DraftAgreementManagementController;
use App\Http\Controllers\EditAgreementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RenewalAgreementController;
use App\Http\Controllers\ViewAgreementController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::post('/select-organisation', 'selectOrganisation')->name('select-organisation');
    Route::get('/agreements', 'allAgreements')->name('all-agreements');
    Route::get('/agreements/data', 'agreementsData')->name('agreements.data');
    Route::get('/drafts/data', 'draftAgreementsData')->name('drafts.data');
});

Route::controller(AddAgreementController::class)->group(function () {
    Route::get('add-agreement', 'index')->name('add-agreement');
    Route::get('agreement-types/{category}', 'getAgreementTypes')
        ->name('agreement.types');
    Route::post('agreements', 'store')
        ->name('agreements.store');
});

Route::controller(DraftAgreementManagementController::class)->group(function () {
    Route::get('draft-agreement', 'index')->name('draft-agreement');
    Route::post('draft-agreements', 'store')
        ->name('draft-agreements.store');
});

Route::controller(EditAgreementController::class)->group(function () {
    Route::get('edit-agreement/{agreementId}', 'editAgreement')->name('edit-agreement');
    Route::put('agreements/{agreement}', 'updateAgreement')
        ->name('agreements.update');
});

Route::controller(RenewalAgreementController::class)->group(function () {
    Route::get('renewal-agreement/{agreementId}', 'renewalAgreement')->name('renewal-agreement');
    Route::post('agreements/{agreementId}/renew', 'renewAgreement')
        ->name('agreements.renew');
});

Route::controller(ViewAgreementController::class)->group(function () {
    Route::get('view-agreement/{agreementId}', 'index')->name('view-agreement');
    Route::get('agreements/{id}/history', 'history')->name('agreements.history');
    Route::delete('agreements/{agreement}', 'destroy')->name('agreements.destroy');
});

Route::fallback(function () {
    return redirect()->route('home');
});
