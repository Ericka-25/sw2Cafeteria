<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\SalesAnalyticsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas del asistente inteligente
Route::prefix('assistant')->group(function () {
    // Procesar consulta en lenguaje natural (texto o voz)
    Route::post('/query', [AssistantController::class, 'processQuery']);

    // Obtener métricas específicas
    Route::get('/metrics', [AssistantController::class, 'getMetrics']);
});

// Rutas adicionales para consultas directas (opcional)
Route::prefix('analytics')->group(function () {
    // Ventas
    Route::get('/sales/top', [SalesAnalyticsController::class, 'getTopSales']);
    Route::get('/sales/summary', [SalesAnalyticsController::class, 'getSummary']);
    Route::get('/sales/trend', [SalesAnalyticsController::class, 'getTrend']);
    Route::get('/sales/comparison', [SalesAnalyticsController::class, 'getComparison']);

    // Vendedores
    Route::get('/sellers/top', [SalesAnalyticsController::class, 'getTopSellers']);
    Route::get('/sellers/{id}/performance', [SalesAnalyticsController::class, 'getSellerPerformance']);

    // Productos
    Route::get('/products/top', [SalesAnalyticsController::class, 'getTopProducts']);
    Route::get('/products/{id}/sales', [SalesAnalyticsController::class, 'getProductSales']);

    // Sucursales
    Route::get('/branches/performance', [SalesAnalyticsController::class, 'getBranchesPerformance']);
    Route::get('/branches/{id}/details', [SalesAnalyticsController::class, 'getBranchDetails']);
});
