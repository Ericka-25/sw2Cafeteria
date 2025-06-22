<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesAnalytics extends Model
{
    /**
     * Obtener las mejores ventas por período
     */
    public static function getTopSalesByPeriod($period = 'day', $limit = 10)
    {
        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('v.estado', true)
            ->select(
                'v.id',
                'v.fecha',
                'v.total',
                'u.name as vendedor',
                's.direccion as sucursal'
            )
            ->orderBy('v.total', 'desc')
            ->limit($limit);

        switch ($period) {
            case 'day':
                $query->whereDate('v.fecha', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
        }

        return $query->get();
    }

    /**
     * Obtener el vendedor con más ventas
     */
    public static function getTopSeller($period = 'month')
    {
        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->where('v.estado', true)
            ->select(
                'u.id',
                'u.name',
                'u.email',
                DB::raw('COUNT(v.id) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_vendido')
            )
            ->groupBy('u.id', 'u.name', 'u.email')
            ->orderBy('total_vendido', 'desc');

        switch ($period) {
            case 'day':
                $query->whereDate('v.fecha', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'all':
                // No filter
                break;
        }

        return $query->first();
    }

    /**
     * Obtener productos más vendidos
     */
    public static function getTopProducts($limit = 10, $period = 'month')
    {
        $query = DB::table('det_ventas as dv')
            ->join('productos as p', 'dv.idproducto', '=', 'p.id')
            ->join('ventas as v', 'dv.idventa', '=', 'v.id')
            ->where('dv.estado', true)
            ->where('v.estado', true)
            ->select(
                'p.id',
                'p.nombre',
                'p.precio_venta',
                DB::raw('SUM(dv.cantidad) as total_cantidad'),
                DB::raw('SUM(dv.total) as total_vendido')
            )
            ->groupBy('p.id', 'p.nombre', 'p.precio_venta')
            ->orderBy('total_cantidad', 'desc')
            ->limit($limit);

        switch ($period) {
            case 'day':
                $query->whereDate('v.fecha', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
        }

        return $query->get();
    }

    /**
     * Análisis de ventas por sucursal
     */
    public static function getSalesBySucursal($period = 'month')
    {
        $query = DB::table('ventas as v')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('v.estado', true)
            ->select(
                's.id',
                's.direccion',
                's.zona',
                DB::raw('COUNT(v.id) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_vendido')
            )
            ->groupBy('s.id', 's.direccion', 's.zona')
            ->orderBy('total_vendido', 'desc');

        switch ($period) {
            case 'day':
                $query->whereDate('v.fecha', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
        }

        return $query->get();
    }

    /**
     * Obtener clientes frecuentes (basado en cantidad de compras)
     * Nota: Como no hay tabla de clientes, se analiza por frecuencia de ventas
     */
    public static function getFrequentSales($period = 'month')
    {
        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->where('v.estado', true)
            ->select(
                DB::raw('DATE(v.fecha) as fecha_venta'),
                DB::raw('COUNT(*) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_dia')
            )
            ->groupBy('fecha_venta')
            ->orderBy('cantidad_ventas', 'desc')
            ->limit(10);

        switch ($period) {
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
        }

        return $query->get();
    }





    public static function getTopSalesByDates($startDate = null, $endDate = null, $limit = 10)
    {
        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('v.estado', true)
            ->select(
                'v.id',
                'v.fecha',
                'v.total',
                'u.name as vendedor',
                's.direccion as sucursal'
            )
            ->orderBy('v.total', 'desc')
            ->limit($limit);

        if ($startDate && $endDate) {
            $query->whereBetween('v.fecha', [$startDate, $endDate]);
        }

        return $query->get();
    }


    public static function getTopSellerByDates($startDate = null, $endDate = null)
    {
        $sellers = self::getTopSellersByDates($startDate, $endDate, 1);
        return $sellers->first();
    }

    /**
     * Obtener un resumen de ventas por sucursal en un rango de fechas
     */
    public static function getSalesSummaryByBranch($startDate = null, $endDate = null)
    {
        $query = DB::table('ventas as v')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('v.estado', true)
            ->select(
                's.id',
                's.direccion as sucursal',
                DB::raw('COUNT(v.id) as total_ventas'),
                DB::raw('SUM(v.total) as monto_total')
            )
            ->groupBy('s.id', 's.direccion')
            ->orderByDesc('monto_total');

        if ($startDate && $endDate) {
            $query->whereBetween('v.fecha', [$startDate, $endDate]);
        }

        return $query->get();
    }

    public static function getTopSellersByPeriod($period = 'month', $limit = 10)
    {
        $query = DB::table('ventas as v')
            ->join('users as u', 'v.idusuario', '=', 'u.id')
            ->where('v.estado', true)
            ->select(
                'u.id',
                'u.name',
                'u.email',
                DB::raw('COUNT(v.id) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_vendido')
            )
            ->groupBy('u.id', 'u.name', 'u.email')
            ->orderBy('total_vendido', 'desc')
            ->limit($limit);

        switch ($period) {
            case 'day':
                $query->whereDate('v.fecha', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('v.fecha', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('v.fecha', Carbon::now()->month)
                      ->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('v.fecha', Carbon::now()->year);
                break;
            case 'all':
                // No filter
                break;
        }

        return $query->get();
    }

    public static function getTopSellersByDates($startDate = null, $endDate = null, $limit = 10)
    {
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

        if ($startDate && $endDate) {
            $query->whereBetween('v.fecha', [$startDate, $endDate]);
        }

        return $query->get();
    }

        /**
     * Obtener productos más vendidos por fechas
     */
    public static function getTopProductsByDates($startDate = null, $endDate = null, $limit = 10)
    {
        $query = DB::table('det_ventas as dv')
            ->join('productos as p', 'dv.idproducto', '=', 'p.id')
            ->join('ventas as v', 'dv.idventa', '=', 'v.id')
            ->where('dv.estado', true)
            ->where('v.estado', true)
            ->select(
                'p.id',
                'p.nombre',
                'p.precio_venta',
                DB::raw('SUM(dv.cantidad) as total_cantidad'),
                DB::raw('SUM(dv.total) as total_vendido'),
                DB::raw('COUNT(DISTINCT v.id) as numero_ventas')
            )
            ->groupBy('p.id', 'p.nombre', 'p.precio_venta')
            ->orderBy('total_cantidad', 'desc')
            ->limit($limit);

        if ($startDate && $endDate) {
            $query->whereBetween('v.fecha', [$startDate, $endDate]);
        }

        return $query->get();
    }

     /**
     * Análisis de ventas por sucursal con fechas
     */
    public static function getSalesBySucursalByDates($startDate = null, $endDate = null)
    {
        $query = DB::table('ventas as v')
            ->join('sucursales as s', 'v.idsucursal', '=', 's.id')
            ->where('v.estado', true)
            ->select(
                's.id',
                's.direccion',
                's.zona',
                's.celular',
                DB::raw('COUNT(v.id) as cantidad_ventas'),
                DB::raw('SUM(v.total) as total_vendido'),
                DB::raw('AVG(v.total) as promedio_venta')
            )
            ->groupBy('s.id', 's.direccion', 's.zona', 's.celular')
            ->orderBy('total_vendido', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('v.fecha', [$startDate, $endDate]);
        }

        return $query->get();
    }

    /**
     * Resumen general de ventas
     */
    public static function getSalesSummary($startDate = null, $endDate = null)
    {
        $query = DB::table('ventas')
            ->where('estado', true);

        if ($startDate && $endDate) {
            $query->whereBetween('fecha', [$startDate, $endDate]);
        }

        $summary = $query->select(
            DB::raw('COUNT(*) as total_ventas'),
            DB::raw('SUM(total) as monto_total'),
            DB::raw('AVG(total) as promedio_venta'),
            DB::raw('MAX(total) as venta_maxima'),
            DB::raw('MIN(total) as venta_minima')
        )->first();

        return $summary;
    }

    /**
     * Tendencia de ventas por período
     */
    public static function getSalesTrend($period = 'daily', $days = 30)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($days);

        $query = DB::table('ventas')
            ->where('estado', true)
            ->whereBetween('fecha', [$startDate, $endDate]);

        switch ($period) {
            case 'daily':
                $query->select(
                    DB::raw('DATE(fecha) as periodo'),
                    DB::raw('COUNT(*) as cantidad'),
                    DB::raw('SUM(total) as total')
                )->groupBy('periodo');
                break;
            case 'weekly':
                $query->select(
                    DB::raw('EXTRACT(YEAR FROM fecha) as año'),
                    DB::raw('EXTRACT(WEEK FROM fecha) as semana'),
                    DB::raw('COUNT(*) as cantidad'),
                    DB::raw('SUM(total) as total')
                )->groupBy('año', 'semana');
                break;
            case 'monthly':
                $query->select(
                    DB::raw('EXTRACT(YEAR FROM fecha) as año'),
                    DB::raw('EXTRACT(MONTH FROM fecha) as mes'),
                    DB::raw('COUNT(*) as cantidad'),
                    DB::raw('SUM(total) as total')
                )->groupBy('año', 'mes');
                break;
        }

        return $query->orderBy('periodo')->get();
    }

    /**
     * Tendencia de ventas por fechas específicas
     */
    public static function getSalesTrendByDates($startDate, $endDate)
    {
        $query = DB::table('ventas')
            ->where('estado', true)
            ->whereBetween('fecha', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(fecha) as periodo'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('periodo')
            ->orderBy('periodo');

        return $query->get();
    }

    /**
     * Comparación de ventas entre períodos
     */
    public static function compareSales($currentPeriod = 'month', $previousPeriod = 'month')
    {
        $current = self::getSalesSummary(
            Carbon::now()->startOf($currentPeriod),
            Carbon::now()->endOf($currentPeriod)
        );

        $previous = self::getSalesSummary(
            Carbon::now()->subMonth()->startOf($previousPeriod),
            Carbon::now()->subMonth()->endOf($previousPeriod)
        );

        $comparison = [
            'periodo_actual' => $current,
            'periodo_anterior' => $previous,
            'variacion_porcentual' => 0,
            'variacion_absoluta' => 0
        ];

        if ($previous->monto_total > 0) {
            $comparison['variacion_porcentual'] =
                (($current->monto_total - $previous->monto_total) / $previous->monto_total) * 100;
            $comparison['variacion_absoluta'] = $current->monto_total - $previous->monto_total;
        }

        return $comparison;
    }

    /**
     * Comparación de ventas por fechas específicas
     */
    public static function compareSalesByDates($temporalInfo)
    {
        // Obtener período actual
        $current = self::getSalesSummary(
            $temporalInfo['start_date'],
            $temporalInfo['end_date']
        );

        // Calcular período anterior basado en el tipo
        $previousStart = null;
        $previousEnd = null;

        if ($temporalInfo['specific_year'] && $temporalInfo['specific_month']) {
            // Comparar con el mes anterior
            $previousDate = Carbon::create($temporalInfo['specific_year'], $temporalInfo['specific_month'], 1)->subMonth();
            $previousStart = $previousDate->copy()->startOfMonth();
            $previousEnd = $previousDate->copy()->endOfMonth();
        } elseif ($temporalInfo['specific_year']) {
            // Comparar con el año anterior
            $previousStart = Carbon::create($temporalInfo['specific_year'] - 1, 1, 1)->startOfYear();
            $previousEnd = Carbon::create($temporalInfo['specific_year'] - 1, 12, 31)->endOfYear();
        } else {
            // Comparar períodos relativos
            switch ($temporalInfo['period']) {
                case 'week':
                    $previousStart = Carbon::now()->subWeek()->startOfWeek();
                    $previousEnd = Carbon::now()->subWeek()->endOfWeek();
                    break;
                case 'month':
                    $previousStart = Carbon::now()->subMonth()->startOfMonth();
                    $previousEnd = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'year':
                    $previousStart = Carbon::now()->subYear()->startOfYear();
                    $previousEnd = Carbon::now()->subYear()->endOfYear();
                    break;
                default:
                    // Para otros casos, restar el mismo período de tiempo
                    if ($temporalInfo['start_date'] && $temporalInfo['end_date']) {
                        $diff = $temporalInfo['start_date']->diffInDays($temporalInfo['end_date']);
                        $previousStart = $temporalInfo['start_date']->copy()->subDays($diff + 1);
                        $previousEnd = $temporalInfo['end_date']->copy()->subDays($diff + 1);
                    }
            }
        }

        $previous = self::getSalesSummary($previousStart, $previousEnd);

        $comparison = [
            'periodo_actual' => $current,
            'periodo_anterior' => $previous,
            'variacion_porcentual' => 0,
            'variacion_absoluta' => 0,
            'fechas_actual' => [
                'inicio' => $temporalInfo['start_date'] ? $temporalInfo['start_date']->format('Y-m-d') : null,
                'fin' => $temporalInfo['end_date'] ? $temporalInfo['end_date']->format('Y-m-d') : null
            ],
            'fechas_anterior' => [
                'inicio' => $previousStart ? $previousStart->format('Y-m-d') : null,
                'fin' => $previousEnd ? $previousEnd->format('Y-m-d') : null
            ]
        ];

        if ($previous && $previous->monto_total > 0) {
            $comparison['variacion_porcentual'] =
                (($current->monto_total - $previous->monto_total) / $previous->monto_total) * 100;
            $comparison['variacion_absoluta'] = $current->monto_total - $previous->monto_total;
        }

        return $comparison;
    }

}