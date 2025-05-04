<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PayementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ContractController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::middleware('auth:sanctum')->group(function () {

// building routes
Route::post('/buildings/create', [BuildingController::class, 'createBuilding']);
Route::put('/buildings/update/{id}', [BuildingController::class, 'updateBuilding']);
Route::delete('/buildings', [BuildingController::class, 'deleteBuilding']);
Route::get('/buildings', [BuildingController::class, 'getBuildings']);

// unit routes
Route::post('/units/create', [UnitController::class, 'createUnit']);
Route::put('/units/update/{id}', [UnitController::class, 'updateUnit']);
Route::delete('/units', [UnitController::class, 'deleteUnit']);
Route::get('/units', [UnitController::class, 'getUnits']);

// payment routes
Route::post('/payments/create', [PayementController::class, 'createPayment']);
Route::put('/payments/update/{id}', [PayementController::class, 'updatePayment']);
Route::delete('/payments', [PayementController::class, 'deletePayment']);
Route::get('/payments', [PayementController::class, 'getPayments']);


// contact routes
Route::post('/contacts', [ContactController::class, 'Contact']);
// });

// manager routes
Route::post('/managers/create', [ManagerController::class, 'createManager']);
Route::put('/managers/update/{id}', [ManagerController::class, 'updateManager']);
Route::delete('/managers', [ManagerController::class, 'deleteManager']);
Route::get('/managers', [ManagerController::class, 'getManagers']);

// tenant routes
Route::post('/tenants/create', [TenantController::class, 'createTenant']);
Route::put('/tenants/update/{id}', [TenantController::class, 'updateTenant']);
Route::delete('/tenants', [TenantController::class, 'deleteTenant']);
Route::get('/tenants', [TenantController::class, 'getTenants']);

// contract routes
Route::post('/contracts/create', [ContractController::class, 'createContract']);
Route::put('/contracts/update', [ContractController::class, 'updateContract']);
Route::delete('/contracts', [ContractController::class, 'deleteContract']);
Route::get('/contracts', [ContractController::class, 'getContracts']);
Route::get('/contacts/{id}', [ContactController::class, 'getContactById']);
Route::put('/contacts/{id}', [ContactController::class, 'updateContact']);
Route::delete('/contacts/{id}', [ContactController::class, 'deleteContact']);