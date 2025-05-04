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
Route::post('/buildings/create', [BuildingController::class, 'createBuilding']); // tested
Route::put('/buildings/update/{id}', [BuildingController::class, 'updateBuilding']); // tested
Route::delete('/buildings/delete/{id}', [BuildingController::class, 'deleteBuilding']); // tested
Route::get('/buildings', [BuildingController::class, 'getAllBuildings']); // tested
Route::get('/buildings/{id}', [BuildingController::class, 'getBuildingById']); // tested
Route::get('/buildings/manager/{id}', [BuildingController::class, 'getBuildingsByManagerId']); // tested

// unit routes
Route::post('/units/create', [UnitController::class, 'createUnit']); // tested
Route::put('/units/update/{id}', [UnitController::class, 'updateUnit']); // tested
Route::delete('/units/delete/{id}', [UnitController::class, 'deleteUnit']);  // tested
Route::get('/units', [UnitController::class, 'getAllUnits']); // tested
Route::get('/units/{id}', [UnitController::class, 'getUnitById']); // tested
Route::get('/units/building/{id}', [UnitController::class, 'getUnitsByBuildingId']); // tested


// payment routes
Route::post('/payments/create', [PayementController::class, 'createPayment']); // tested
Route::put('/payments/update/{id}', [PayementController::class, 'updatePayment']); // tested
Route::delete('/payments/delete/', [PayementController::class, 'deletePayement']);  // tested
Route::get('/payments/{id}', [PayementController::class, 'getPaymentById']); // tested
Route::get('/payments/unit/{id}', [PayementController::class, 'getPaymentsByUnitId']);
Route::get('/payments/tenant/{id}', [PayementController::class, 'getPaymentsByTenantId']);
Route::get('/payments', [PayementController::class, 'getAllPayments']);


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