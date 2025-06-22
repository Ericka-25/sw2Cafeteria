<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesAnalytics;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesAnalyticsController extends Controller
{
    /**
     * Obtener las mejores ventas
     */
    public function getTopSales(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $period = $request->period ?? 'month';
        $limit = $request->limit ?? 10;

        $sales = SalesAnalytics::getTopSalesByPeriod($period, $limit);

        return response()->json([
            'success' => true,
            'period' => $period,
            'count' => $sales->count(),
            'data' => $sales
        ]);
    }

    /**
     * Obtener resumen de ventas
     */
    public function getSummary(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $summary = SalesAnalytics::getSalesSummary(
            $request->start_date,
            $request->end_date
        );

        return response()->json([
            'success' => true,
            'period' => [
                'start' => $request->start_date ?? 'inicio',
                'end' => $request->end_date ?? 'actual'
            ],
            'data' => $summary
        ]);
    }

    /**
     * Obtener tendencia de ventas
     */
    public function getTrend(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:daily,weekly,monthly',
            'days' => 'nullable|integer|min:7|max:365'
        ]);

        $period = $request->period ?? 'daily';
        $days = $request->days ?? 30;

        $trend = SalesAnalytics::getSalesTrend($period, $days);

        return response()->json([
            'success' => true,
            'period' => $period,
            'days' => $days,
            'data' => $trend
        ]);
    }

    /**
     * Comparar ventas entre períodos
     */
    public function getComparison(Request $request)
    {
        $request->validate([
            'current_period' => 'nullable|string|in:week,month,year',
            'previous_period' => 'nullable|string|in:week,month,year'
        ]);

        $comparison = SalesAnalytics::compareSales(
            $request->current_period ?? 'month',
            $request->previous_period ?? 'month'
        );

        return response()->json([
            'success' => true,
            'data' => $comparison
        ]);
    }

    /**
     * Obtener mejores vendedores
     */
    public function getTopSellers(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year,all',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        $period = $request->period ?? 'month';
        $limit = $request->limit ?? 10;

        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->where('v.estado', true)
            ->select(
                'u.id',
                'u.name',
                'u.email',
                DB::raw('COUNT(v.id) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_vendido'),
                DB::raw('AVG(v.total) as promedio_venta')
            )
            ->groupBy('u.id', 'u.name', 'u.email')
            ->orderBy('total_vendido', 'desc')
            ->limit($limit);

        // Aplicar filtro de período
        $this->applyPeriodFilter($query, $period, 'v.fecha');

        $sellers = $query->get();

        return response()->json([
            'success' => true,
            'period' => $period,
            'count' => $sellers->count(),
            'data' => $sellers
        ]);
    }

    /**
     * Obtener rendimiento de un vendedor específico
     */
    public function getSellerPerformance($id, Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year,all'
        ]);

        $period = $request->period ?? 'month';

        // Verificar que el vendedor existe
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vendedor no encontrado'
            ], 404);
        }

        // Obtener estadísticas del vendedor
        $query = DB::table('ventas as v')
            ->where('v.idusuario', $id)
            ->where('v.estado', true);

        $this->applyPeriodFilter($query, $period, 'v.fecha');

        $stats = $query->select(
            DB::raw('COUNT(*) as total_ventas'),
            DB::raw('SUM(total) as monto_total'),
            DB::raw('AVG(total) as promedio_venta'),
            DB::raw('MAX(total) as venta_maxima'),
            DB::raw('MIN(total) as venta_minima')
        )->first();

        // Obtener ventas por día
        $dailySales = DB::table('ventas')
            ->where('idusuario', $id)
            ->where('estado', true)
            ->select(
                DB::raw('DATE(fecha) as fecha'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('fecha')
            ->orderBy('fecha', 'desc')
            ->limit(30)
            ->get();

        return response()->json([
            'success' => true,
            'seller' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'period' => $period,
            'statistics' => $stats,
            'daily_sales' => $dailySales
        ]);
    }

    /**
     * Obtener productos más vendidos
     */
    public function getTopProducts(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $period = $request->period ?? 'month';
        $limit = $request->limit ?? 20;

        $products = SalesAnalytics::getTopProducts($limit, $period);

        return response()->json([
            'success' => true,
            'period' => $period,
            'count' => $products->count(),
            'data' => $products
        ]);
    }

    /**
     * Obtener ventas de un producto específico
     */
    public function getProductSales($id, Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year,all'
        ]);

        $period = $request->period ?? 'month';

        // Verificar que el producto existe
        $producto = DB::table('productos')->where('id', $id)->first();
        if (!$producto) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        // Obtener estadísticas del producto
        $query = DB::table('det_ventas as dv')
            ->join('ventas as v', 'dv.idventa', '=', 'v.id')
            ->where('dv.idproducto', $id)
            ->where('dv.estado', true)
            ->where('v.estado', true);

        $this->applyPeriodFilter($query, $period, 'v.fecha');

        $stats = $query->select(
            DB::raw('SUM(dv.cantidad) as total_cantidad'),
            DB::raw('SUM(dv.total) as total_vendido'),
            DB::raw('COUNT(DISTINCT v.id) as numero_ventas')
        )->first();

        // Ventas por sucursal
        $salesByBranch = DB::table('det_ventas as dv')
            ->join('ventas as v', 'dv.idventa', '=', 'v.id')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('dv.idproducto', $id)
            ->where('dv.estado', true)
            ->where('v.estado', true)
            ->select(
                's.id',
                's.direccion',
                DB::raw('SUM(dv.cantidad) as cantidad'),
                DB::raw('SUM(dv.total) as total')
            )
            ->groupBy('s.id', 's.direccion')
            ->get();

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio_venta' => $producto->precio_venta
            ],
            'period' => $period,
            'statistics' => $stats,
            'sales_by_branch' => $salesByBranch
        ]);
    }

    /**
     * Obtener rendimiento de sucursales
     */
    public function getBranchesPerformance(Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year'
        ]);

        $period = $request->period ?? 'month';
        $branches = SalesAnalytics::getSalesBySucursal($period);

        return response()->json([
            'success' => true,
            'period' => $period,
            'count' => $branches->count(),
            'data' => $branches
        ]);
    }

    /**
     * Obtener detalles de una sucursal
     */
    public function getBranchDetails($id, Request $request)
    {
        $request->validate([
            'period' => 'nullable|string|in:day,week,month,year,all'
        ]);

        $period = $request->period ?? 'month';

        // Verificar que la sucursal existe
        $sucursal = DB::table('sucursales')->where('id', $id)->first();
        if (!$sucursal) {
            return response()->json([
                'success' => false,
                'message' => 'Sucursal no encontrada'
            ], 404);
        }

        // Estadísticas generales
        $query = DB::table('ventas')
            ->where('idsucursal', $id)
            ->where('estado', true);

        $this->applyPeriodFilter($query, $period, 'fecha');

        $stats = $query->select(
            DB::raw('COUNT(*) as total_ventas'),
            DB::raw('SUM(total) as monto_total'),
            DB::raw('AVG(total) as promedio_venta')
        )->first();

        // Top vendedores de la sucursal
        $topSellers = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->where('v.idsucursal', $id)
            ->where('v.estado', true)
            ->select(
                'u.id',
                'u.name',
                DB::raw('COUNT(v.id) as ventas'),
                DB::raw('SUM(v.total) as total')
            )
            ->groupBy('u.id', 'u.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Top productos de la sucursal
        $topProducts = DB::table('det_ventas as dv')
            ->join('ventas as v', 'dv.idventa', '=', 'v.id')
            ->join('productos as p', 'dv.idproducto', '=', 'p.id')
            ->where('v.idsucursal', $id)
            ->where('v.estado', true)
            ->where('dv.estado', true)
            ->select(
                'p.id',
                'p.nombre',
                DB::raw('SUM(dv.cantidad) as cantidad'),
                DB::raw('SUM(dv.total) as total')
            )
            ->groupBy('p.id', 'p.nombre')
            ->orderBy('cantidad', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'branch' => [
                'id' => $sucursal->id,
                'direccion' => $sucursal->direccion,
                'zona' => $sucursal->zona,
                'celular' => $sucursal->celular
            ],
            'period' => $period,
            'statistics' => $stats,
            'top_sellers' => $topSellers,
            'top_products' => $topProducts
        ]);
    }

    /**
     * Aplicar filtro de período a una consulta
     */
    private function applyPeriodFilter($query, $period, $dateColumn)
    {
        switch ($period) {
            case 'day':
                $query->whereDate($dateColumn, Carbon::today());
                break;
            case 'week':
                $query->whereBetween($dateColumn, [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;
            case 'month':
                $query->whereMonth($dateColumn, Carbon::now()->month)
                      ->whereYear($dateColumn, Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear($dateColumn, Carbon::now()->year);
                break;
            case 'all':
                // No filter
                break;
        }
    }
}