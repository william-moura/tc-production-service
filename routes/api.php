<?php

use App\Presentation\Http\Controllers\ProductionOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/production-orders')->group(function (): void {
    Route::get('/', [ProductionOrderController::class, 'index']);
    Route::get('/{id}', [ProductionOrderController::class, 'show']);
    Route::get('/work-order/{workOrderId}', [ProductionOrderController::class, 'findByWorkOrder']);

    Route::post('/work-order/{workOrderId}/start', [ProductionOrderController::class, 'start']);
    Route::post('/work-order/{workOrderId}/complete', [ProductionOrderController::class, 'complete']);
    Route::post('/work-order/{workOrderId}/abort', [ProductionOrderController::class, 'abort']);
});
