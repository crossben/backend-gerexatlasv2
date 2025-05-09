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
Route::get('/payments/unit/{id}', [PayementController::class, 'getPaymentsByUnitId']); // tested
Route::get('/payments/tenant/{id}', [PayementController::class, 'getPaymentsByTenantId']); // tested
Route::get('/payments/building/{id}', [PayementController::class, 'getPaymentsByBuildingId']); // tested
Route::get('/payments', [PayementController::class, 'getAllPayments']); // tested

// contract routes
Route::post('/contracts/create', [ContractController::class, 'createContract']); // tested
Route::put('/contracts/update/{id}', [ContractController::class, 'updateContract']); // tested but not fully
Route::delete('/contracts/delete/{id}', [ContractController::class, 'deleteContract']); // tested
Route::get('/contracts/manager/{id}', [ContractController::class, 'getContractsByManagerId']); // tested
Route::get('/contracts/tenant/{id}', [ContractController::class, 'getContractsByTenantId']); // tested
Route::get('/contracts/unit/{id}', [ContractController::class, 'getContractsByUnitId']); // tested
Route::get('/contracts/{id}', [ContractController::class, 'getContractById']); // tested

// tenant routes
Route::post('/tenants/create', [TenantController::class, 'createTenant']); // tested
Route::put('/tenants/update/{id}', [TenantController::class, 'updateTenant']); // tested
Route::delete('/tenants/delete/{id}', [TenantController::class, 'deleteTenant']); // tested
Route::get('/tenants/{id}', [TenantController::class, 'getTenantById']); // tested 
Route::get('/tenants', [TenantController::class, 'getAllTenants']); // tested
Route::get('/tenants/building/{id}', [TenantController::class, 'getTenantsByBuilding']); // tested


// manager routes 
Route::post('/managers/register', [ManagerController::class, 'RegisterManager']);
Route::post('/managers/login', [ManagerController::class, 'LoginManager']);
Route::put('/managers/update/{id}', [ManagerController::class, 'updateManager']);
Route::delete('/managers/delete/{id}', [ManagerController::class, 'deleteManager']);
Route::get('/managers', [ManagerController::class, 'getManagers']);

Route::post('login', [ManagerController::class, 'loginManager']);
Route::post('register', [ManagerController::class, 'RegisterManager']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('profile', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [ManagerController::class, 'logout']);
});
// });



// contact routes
Route::post('/contacts', [ContactController::class, 'Contact']); // tested