<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesAnalytics;
use Carbon\Carbon;

class AssistantController extends Controller
{
    /**
     * Procesar consulta del asistente inteligente
     */
    public function processQuery(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'type' => 'nullable|string|in:text,voice'
        ]);

        $query = strtolower($request->input('query'));
        $response = $this->analyzeQuery($query);

        return response()->json([
            'success' => true,
            'query' => $request->query,
            'response' => $response['message'],
            'data' => $response['data'],
            'type' => $response['type']
        ]);
    }

    /**
     * Analizar y procesar la consulta
     */
    private function analyzeQuery($query)
    {
        // Detectar si es singular o plural
        $isPlural = $this->detectPlural($query);

        // Palabras clave para identificar el tipo de consulta
        $keywords = [
            'mejor_venta' => [
                'mejor venta', 'mejores ventas', 'venta mas alta', 'venta más alta', 'ventas mas altas', 'ventas más altas',
                'mayor venta', 'mayores ventas', 'top venta', 'top ventas', 'venta mayor', 'ventas mayores',
                'venta grande', 'ventas grandes', 'venta maxima', 'venta máxima', 'ventas maximas', 'ventas máximas',
                'record venta', 'record ventas', 'récord venta', 'récord ventas', 'venta record', 'ventas record',
                'mejor vnta', 'mjor venta', 'mejor benta', 'ventas altas', 'venta alta', 'la mas alta',
                'las mas altas', 'la mayor', 'las mayores', 'cual fue la mayor', 'cual fue la mejor',
                'cuales fueron las mejores', 'cuales fueron las mayores', 'venta superior', 'ventas superiores',
                'venta mas grande', 'ventas mas grandes', 'venta mas cara', 'ventas mas caras'
            ],

            'vendedor_top' => [
                'mejor vendedor', 'mejores vendedores', 'vendió más', 'vendio mas', 'vendieron mas', 'vendieron más',
                'top vendedor', 'top vendedores', 'vendedor estrella', 'vendedores estrella', 'quien vendio',
                'quien vendió', 'quienes vendieron', 'que usuario', 'qué usuario', 'cual usuario', 'cuál usuario',
                'que vendedor', 'qué vendedor', 'cual vendedor', 'cuál vendedor', 'usuario que vendio',
                'usuario que vendió', 'empleado que vendio', 'empleado que vendió', 'mejor empleado',
                'mejores empleados', 'vendedor top', 'vendedores top', 'el que vendio mas', 'el que vendio más',
                'los que vendieron mas', 'los que vendieron más', 'quien fue el mejor', 'quienes fueron los mejores',
                'vendedor campeon', 'vendedor campeón', 'vendedores campeones', 'el mejor', 'los mejores',
                'vendio mas', 'bendio mas', 'vendio max', 'vendedor estreya', 'bendedor', 'vendedro'
            ],

            'productos_top' => [
                'productos más vendidos', 'productos mas vendidos', 'producto más vendido', 'producto mas vendido',
                'top productos', 'top producto', 'mejores productos', 'mejor producto', 'que productos',
                'qué productos', 'cuales productos', 'cuáles productos', 'que se vendio', 'qué se vendió',
                'que se vendieron', 'qué se vendieron', 'articulos mas vendidos', 'artículos más vendidos',
                'articulo mas vendido', 'artículo más vendido', 'items mas vendidos', 'items más vendidos',
                'productos top', 'producto top', 'mas vendido', 'más vendido', 'mas vendidos', 'más vendidos',
                'lo que mas se vendio', 'lo que más se vendió', 'lo mas vendido', 'lo más vendido',
                'productos estrella', 'producto estrella', 'productos exitosos', 'productos de exito',
                'que cosa se vendio', 'que cosas se vendieron', 'productos populares', 'mas popular',
                'más popular', 'mas populares', 'más populares', 'producto mas salido', 'productos mas salidos',
                'q productos', 'q se vendio', 'prod mas vendidos', 'prods mas vendidos', 'prodcutos', 'porductos'
            ],

            'sucursal' => [
                'sucursal', 'sucursales', 'tienda', 'tiendas', 'local', 'locales', 'sede', 'sedes',
                'punto de venta', 'puntos de venta', 'agencia', 'agencias', 'oficina', 'oficinas',
                'establecimiento', 'establecimientos', 'negocio', 'negocios', 'centro', 'centros',
                'punto', 'puntos', 'lugar', 'lugares', 'ubicacion', 'ubicación', 'ubicaciones',
                'cual sucursal', 'cuál sucursal', 'que sucursal', 'qué sucursal', 'cual tienda',
                'que tienda', 'cual local', 'que local', 'donde se vendio', 'dónde se vendió',
                'en que sucursal', 'en qué sucursal', 'sucursl', 'scursal', 'sucrsal', 'tienda'
            ],

            'resumen' => [
                'resumen', 'resumenes', 'total', 'totales', 'cuanto', 'cuánto', 'cuantos', 'cuántos',
                'general', 'generales', 'estadisticas', 'estadísticas', 'estadistica', 'estadística',
                'reporte', 'reportes', 'informe', 'informes', 'resultado', 'resultados', 'balance',
                'balances', 'sumario', 'sumarios', 'consolidado', 'consolidados', 'global', 'globales',
                'todo', 'todos', 'completo', 'completos', 'integral', 'integrales', 'cifras', 'cifra',
                'numeros', 'números', 'numero', 'número', 'datos', 'dato', 'informacion', 'información',
                'cuanto se vendio', 'cuánto se vendió', 'cuanto fue', 'cuánto fue', 'total de ventas',
                'total ventas', 'resumen general', 'resumen total', 'estadist', 'stats', 'estad',
                'rsumen', 'rezumen', 'resumne', 'totla', 'toatl', 'cuato', 'caunto'
            ],

            'tendencia' => [
                'tendencia', 'tendencias', 'evolucion', 'evolución', 'evoluciones', 'comportamiento',
                'comportamientos', 'como van', 'cómo van', 'como va', 'cómo va', 'progreso', 'progresos',
                'desarrollo', 'desarrollos', 'crecimiento', 'crecimientos', 'patron', 'patrón',
                'patrones', 'historial', 'historiales', 'historico', 'histórico', 'trayectoria',
                'trayectorias', 'movimiento', 'movimientos', 'direccion', 'dirección', 'rumbo',
                'curso', 'marcha', 'avance', 'avances', 'proyeccion', 'proyección', 'proyecciones',
                'como vamos', 'cómo vamos', 'como estamos', 'cómo estamos', 'van bien', 'van mal',
                'subiendo', 'bajando', 'mejorando', 'empeorando', 'analisis', 'análisis',
                'tendncia', 'tedencia', 'evolucon', 'comportamineto', 'como ban', 'komo van'
            ],

            'comparar' => [
                'comparar', 'comparacion', 'comparación', 'comparaciones', 'versus', 'vs', 'contra',
                'diferencia', 'diferencias', 'contrastar', 'contraste', 'contrastes', 'confrontar',
                'confrontacion', 'confrontación', 'cotejar', 'cotejo', 'equiparar', 'relacionar',
                'relacion', 'relación', 'entre', 'respecto', 'frente', 'con respecto', 'en comparacion',
                'en comparación', 'comparado', 'comparada', 'comparados', 'comparadas', 'mejor que',
                'peor que', 'mas que', 'más que', 'menos que', 'igual que', 'similar', 'diferente',
                'distinto', 'cambio', 'cambios', 'variacion', 'variación', 'variaciones',
                'comprar', 'comparr', 'conparar', 'conparacion', 'vs.', 'v/s', 'diferncia'
            ]
         ];

        // Extraer información temporal de la consulta
        $temporalInfo = $this->extractTemporalInfo($query);

        // Determinar el límite basado en singular/plural
        $limit = $isPlural ? 10 : 1;

        // Procesar según tipo de consulta
        if ($this->containsKeywords($query, $keywords['mejor_venta'])) {
            return $this->getBestSales($temporalInfo, $limit);
        } elseif ($this->containsKeywords($query, $keywords['vendedor_top'])) {
            return $this->getTopSeller($temporalInfo, $limit);
        } elseif ($this->containsKeywords($query, $keywords['productos_top'])) {
            return $this->getTopProducts($temporalInfo, $limit);
        } elseif ($this->containsKeywords($query, $keywords['sucursal'])) {
            return $this->getSalesBySucursal($temporalInfo);
        } elseif ($this->containsKeywords($query, $keywords['resumen'])) {
            return $this->getSalesSummary($temporalInfo);
        } elseif ($this->containsKeywords($query, $keywords['tendencia'])) {
            return $this->getSalesTrend($temporalInfo);
        } elseif ($this->containsKeywords($query, $keywords['comparar'])) {
            return $this->compareSales($temporalInfo);
        } else {
            return $this->getGeneralInfo();
        }
    }

    /**
     * Detectar si la consulta es plural
     */
    private function detectPlural($query)
    {
        $pluralIndicators = [
            'cuales son', 'cuáles son', 'las mejores', 'los mejores',
            'ventas', 'vendedores', 'productos', 'todas las', 'todos los',
            'listame', 'lísta', 'muéstrame las', 'muestrame las'
        ];

        foreach ($pluralIndicators as $indicator) {
            if (strpos($query, $indicator) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extraer información temporal de la consulta
     */
    private function extractTemporalInfo($query)
    {
        $info = [
            'period' => null,
            'specific_year' => null,
            'specific_month' => null,
            'specific_date' => null,
            'start_date' => null,
            'end_date' => null,
            'custom_range' => false
        ];

        // Detectar años específicos (2020-2030)
        if (preg_match('/\b(20[2-3][0-9])\b/', $query, $matches)) {
            $info['specific_year'] = (int)$matches[1];
            $info['period'] = 'year';
            $info['custom_range'] = true;
        }

        // Detectar "año pasado", "año anterior"
        if (strpos($query, 'año pasado') !== false || strpos($query, 'año anterior') !== false) {
            $info['specific_year'] = Carbon::now()->year - 1;
            $info['period'] = 'year';
            $info['custom_range'] = true;
        }

        // Detectar meses específicos
        $months = [
            'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
            'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
            'septiembre' => 9, 'setiembre' => 9, 'octubre' => 10,
            'noviembre' => 11, 'diciembre' => 12
        ];

        foreach ($months as $monthName => $monthNumber) {
            if (strpos($query, $monthName) !== false) {
                $info['specific_month'] = $monthNumber;
                $info['period'] = 'month';
                $info['custom_range'] = true;

                // Si no se especificó año, usar el actual
                if (!$info['specific_year']) {
                    // Si el mes ya pasó este año, podría referirse al año pasado
                    if ($monthNumber < Carbon::now()->month) {
                        $info['specific_year'] = Carbon::now()->year;
                    } else {
                        $info['specific_year'] = Carbon::now()->year;
                    }
                }
                break;
            }
        }

        // Detectar "mes pasado", "mes anterior"
        if ((strpos($query, 'mes pasado') !== false || strpos($query, 'mes anterior') !== false) && !$info['specific_month']) {
            $lastMonth = Carbon::now()->subMonth();
            $info['specific_month'] = $lastMonth->month;
            $info['specific_year'] = $lastMonth->year;
            $info['period'] = 'month';
            $info['custom_range'] = true;
        }

        // Detectar fechas específicas (ej: "15 de enero", "del 1 al 15")
        if (preg_match('/(\d{1,2})\s*de\s*(\w+)/', $query, $matches)) {
            $day = (int)$matches[1];
            $monthName = $matches[2];
            if (isset($months[$monthName])) {
                $info['specific_date'] = Carbon::create(
                    $info['specific_year'] ?? Carbon::now()->year,
                    $months[$monthName],
                    $day
                );
                $info['period'] = 'day';
                $info['custom_range'] = true;
            }
        }

        // Detectar períodos relativos si no hay información específica
        if (!$info['custom_range']) {
            $periods = [
                'hoy' => 'day',
                'ayer' => 'yesterday',
                'esta semana' => 'week',
                'semana actual' => 'week',
                'semana pasada' => 'last_week',
                'semana anterior' => 'last_week',
                'este mes' => 'month',
                'mes actual' => 'month',
                'este año' => 'year',
                'año actual' => 'year',
                'este trimestre' => 'quarter',
                'trimestre actual' => 'quarter',
                'últimos 7 días' => 'last_7_days',
                'últimos 30 días' => 'last_30_days',
                'últimos 90 días' => 'last_90_days',
                'todo' => 'all',
                'siempre' => 'all',
                'histórico' => 'all',
                'toda la historia' => 'all'
            ];

            foreach ($periods as $keyword => $value) {
                if (strpos($query, $keyword) !== false) {
                    $info['period'] = $value;
                    break;
                }
            }

            // Si solo dice "semana", "mes", "año" sin más contexto
            if (!$info['period']) {
                if (strpos($query, 'semana') !== false) $info['period'] = 'week';
                elseif (strpos($query, 'mes') !== false) $info['period'] = 'month';
                elseif (strpos($query, 'año') !== false || strpos($query, 'año') !== false) $info['period'] = 'year';
                elseif (strpos($query, 'día') !== false || strpos($query, 'dia') !== false) $info['period'] = 'day';
            }
        }

        // Si no se detectó ningún período, usar mes por defecto
        if (!$info['period']) {
            $info['period'] = 'month';
        }

        // Calcular fechas basadas en la información extraída
        $this->calculateDates($info);

        return $info;
    }

    /**
     * Calcular fechas basadas en la información temporal
     */
    private function calculateDates(&$info)
    {
        // Si hay una fecha específica
        if ($info['specific_date']) {
            $info['start_date'] = $info['specific_date']->copy()->startOfDay();
            $info['end_date'] = $info['specific_date']->copy()->endOfDay();
            return;
        }

        // Si hay año y mes específicos
        if ($info['specific_year'] && $info['specific_month']) {
            $date = Carbon::create($info['specific_year'], $info['specific_month'], 1);
            $info['start_date'] = $date->copy()->startOfMonth();
            $info['end_date'] = $date->copy()->endOfMonth();
            return;
        }

        // Si solo hay año específico
        if ($info['specific_year'] && !$info['specific_month']) {
            $date = Carbon::create($info['specific_year'], 1, 1);
            $info['start_date'] = $date->copy()->startOfYear();
            $info['end_date'] = $date->copy()->endOfYear();
            return;
        }

        // Para períodos relativos
        switch ($info['period']) {
            case 'day':
                $info['start_date'] = Carbon::today();
                $info['end_date'] = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $info['start_date'] = Carbon::yesterday();
                $info['end_date'] = Carbon::yesterday()->endOfDay();
                break;
            case 'week':
                $info['start_date'] = Carbon::now()->startOfWeek();
                $info['end_date'] = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $info['start_date'] = Carbon::now()->subWeek()->startOfWeek();
                $info['end_date'] = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'month':
                $info['start_date'] = Carbon::now()->startOfMonth();
                $info['end_date'] = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $info['start_date'] = Carbon::now()->subMonth()->startOfMonth();
                $info['end_date'] = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'year':
                $info['start_date'] = Carbon::now()->startOfYear();
                $info['end_date'] = Carbon::now()->endOfYear();
                break;
            case 'last_year':
                $info['start_date'] = Carbon::now()->subYear()->startOfYear();
                $info['end_date'] = Carbon::now()->subYear()->endOfYear();
                break;
            case 'quarter':
                $info['start_date'] = Carbon::now()->startOfQuarter();
                $info['end_date'] = Carbon::now()->endOfQuarter();
                break;
            case 'last_7_days':
                $info['start_date'] = Carbon::now()->subDays(7);
                $info['end_date'] = Carbon::now();
                break;
            case 'last_30_days':
                $info['start_date'] = Carbon::now()->subDays(30);
                $info['end_date'] = Carbon::now();
                break;
            case 'last_90_days':
                $info['start_date'] = Carbon::now()->subDays(90);
                $info['end_date'] = Carbon::now();
                break;
            case 'all':
                $info['start_date'] = null;
                $info['end_date'] = null;
                break;
        }
    }

    /**
     * Verificar si la consulta contiene palabras clave
     */
    private function containsKeywords($query, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($query, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtener las mejores ventas
     */
    private function getBestSales($temporalInfo, $limit = 5)
    {
        $sales = SalesAnalytics::getTopSalesByDates(
            $temporalInfo['start_date'],
            $temporalInfo['end_date'],
            $limit
        );

        if ($sales->isEmpty()) {
            $periodDescription = $this->getPeriodDescription($temporalInfo);
            return [
                'message' => "No se encontraron ventas {$periodDescription}. Verifica que existan registros en ese período.",
                'data' => [],
                'type' => 'best_sales'
            ];
        }

        $periodDescription = $this->getPeriodDescription($temporalInfo);

        // Si es singular (limit = 1)
        if ($limit == 1 || $sales->count() == 1) {
            $topSale = $sales->first();
            $message = sprintf(
                "La mejor venta %s fue de Bs. %.2f, realizada por %s en la sucursal %s el %s.",
                $periodDescription,
                $topSale->total,
                $topSale->vendedor,
                $topSale->sucursal,
                Carbon::parse($topSale->fecha)->format('d/m/Y')
            );
        } else {
            // Si es plural
            $message = sprintf(
                "Las %d mejores ventas %s son:\n",
                $sales->count(),
                $periodDescription
            );

            foreach ($sales as $index => $sale) {
                $message .= sprintf(
                    "%d. Bs. %.2f - %s en %s (%s)\n",
                    $index + 1,
                    $sale->total,
                    $sale->vendedor,
                    $sale->sucursal,
                    Carbon::parse($sale->fecha)->format('d/m/Y')
                );
            }
        }

        return [
            'message' => $message,
            'data' => $sales,
            'type' => 'best_sales'
        ];
    }

    /**
     * Obtener el mejor vendedor
     */
    private function getTopSeller($temporalInfo, $limit = 1)
    {
        $sellers = SalesAnalytics::getTopSellersByDates(
            $temporalInfo['start_date'],
            $temporalInfo['end_date'],
            $limit
        );

        if ($sellers->isEmpty()) {
            $periodDescription = $this->getPeriodDescription($temporalInfo);
            return [
                'message' => "No se encontraron vendedores con ventas {$periodDescription}.",
                'data' => [],
                'type' => 'top_seller'
            ];
        }

        $periodDescription = $this->getPeriodDescription($temporalInfo);

        // Si es singular
        if ($limit == 1 || $sellers->count() == 1) {
            $seller = $sellers->first();
            $message = sprintf(
                "El mejor vendedor %s es %s con %d ventas por un total de Bs. %.2f.",
                $periodDescription,
                $seller->name,
                $seller->cantidad_ventas,
                $seller->total_vendido
            );
        } else {
            // Si es plural
            $message = sprintf(
                "Los %d mejores vendedores %s son:\n",
                $sellers->count(),
                $periodDescription
            );

            foreach ($sellers as $index => $seller) {
                $message .= sprintf(
                    "%d. %s - %d ventas, Total: Bs. %.2f\n",
                    $index + 1,
                    $seller->name,
                    $seller->cantidad_ventas,
                    $seller->total_vendido
                );
            }
        }

        return [
            'message' => $message,
            'data' => $sellers,
            'type' => 'top_seller'
        ];
    }

    /**
     * Obtener productos más vendidos
     */
    private function getTopProducts($temporalInfo, $limit = 5)
    {
        $products = SalesAnalytics::getTopProductsByDates(
            $temporalInfo['start_date'],
            $temporalInfo['end_date'],
            $limit
        );

        if ($products->isEmpty()) {
            $periodDescription = $this->getPeriodDescription($temporalInfo);
            return [
                'message' => "No se encontraron productos vendidos {$periodDescription}.",
                'data' => [],
                'type' => 'top_products'
            ];
        }

        $periodDescription = $this->getPeriodDescription($temporalInfo);

        // Si es singular
        if ($limit == 1 || $products->count() == 1) {
            $product = $products->first();
            $message = sprintf(
                "El producto más vendido %s es '%s' con %d unidades vendidas por un total de Bs. %.2f.",
                $periodDescription,
                $product->nombre,
                $product->total_cantidad,
                $product->total_vendido
            );
        } else {
            // Si es plural
            $message = sprintf(
                "Los %d productos más vendidos %s son:\n",
                $products->count(),
                $periodDescription
            );

            foreach ($products as $index => $product) {
                $message .= sprintf(
                    "%d. %s - %d unidades, Total: Bs. %.2f\n",
                    $index + 1,
                    $product->nombre,
                    $product->total_cantidad,
                    $product->total_vendido
                );
            }
        }

        return [
            'message' => $message,
            'data' => $products,
            'type' => 'top_products'
        ];
    }

    /**
     * Obtener ventas por sucursal
     */
    private function getSalesBySucursal($temporalInfo)
    {
        $sucursales = SalesAnalytics::getSalesBySucursalByDates(
            $temporalInfo['start_date'],
            $temporalInfo['end_date']
        );

        if ($sucursales->isEmpty()) {
            $periodDescription = $this->getPeriodDescription($temporalInfo);
            return [
                'message' => "No se encontraron ventas por sucursal {$periodDescription}.",
                'data' => [],
                'type' => 'sales_by_branch'
            ];
        }

        $periodDescription = $this->getPeriodDescription($temporalInfo);
        $message = sprintf(
            "Rendimiento de sucursales %s:\n",
            $periodDescription
        );

        foreach ($sucursales as $index => $sucursal) {
            $message .= sprintf(
                "%d. %s (%s) - %d ventas, Total: Bs. %.2f\n",
                $index + 1,
                $sucursal->direccion,
                $sucursal->zona,
                $sucursal->cantidad_ventas,
                $sucursal->total_vendido
            );
        }

        return [
            'message' => $message,
            'data' => $sucursales,
            'type' => 'sales_by_branch'
        ];
    }

    /**
     * Obtener resumen de ventas
     */
    private function getSalesSummary($temporalInfo)
    {
        $summary = SalesAnalytics::getSalesSummary(
            $temporalInfo['start_date'],
            $temporalInfo['end_date']
        );

        if (!$summary || !$summary->total_ventas) {
            $periodDescription = $this->getPeriodDescription($temporalInfo);
            return [
                'message' => "No se encontraron ventas {$periodDescription}.",
                'data' => $summary,
                'type' => 'summary'
            ];
        }

        $periodDescription = $this->getPeriodDescription($temporalInfo);

        $message = sprintf(
            "📊 Resumen de ventas %s:\n\n" .
            "• Total de ventas: %d\n" .
            "• Monto total: Bs. %.2f\n" .
            "• Promedio por venta: Bs. %.2f\n" .
            "• Venta más alta: Bs. %.2f\n" .
            "• Venta más baja: Bs. %.2f",
            $periodDescription,
            $summary->total_ventas,
            $summary->monto_total,
            $summary->promedio_venta,
            $summary->venta_maxima,
            $summary->venta_minima
        );

        return [
            'message' => $message,
            'data' => $summary,
            'type' => 'summary'
        ];
    }

    /**
     * Obtener tendencia de ventas
     */
    private function getSalesTrend($temporalInfo)
    {
        // Determinar el período de análisis
        $days = 30;
        if ($temporalInfo['start_date'] && $temporalInfo['end_date']) {
            $days = $temporalInfo['start_date']->diffInDays($temporalInfo['end_date']);
            if ($days < 7) $days = 7; // Mínimo 7 días para análisis de tendencia
        }

        $trend = SalesAnalytics::getSalesTrendByDates(
            $temporalInfo['start_date'] ?? Carbon::now()->subDays($days),
            $temporalInfo['end_date'] ?? Carbon::now()
        );

        if ($trend->isEmpty()) {
            return [
                'message' => "No hay datos suficientes para mostrar la tendencia de ventas.",
                'data' => [],
                'type' => 'trend'
            ];
        }

        $totalVentas = $trend->sum('total');
        $promedioDaily = $trend->count() > 0 ? $totalVentas / $trend->count() : 0;

        $periodDescription = $this->getPeriodDescription($temporalInfo);
        $message = sprintf(
            "📈 Tendencia de ventas %s:\n\n" .
            "• Ventas totales: Bs. %.2f\n" .
            "• Promedio diario: Bs. %.2f\n" .
            "• Días analizados: %d\n",
            $periodDescription,
            $totalVentas,
            $promedioDaily,
            $trend->count()
        );

        // Analizar tendencia si hay suficientes datos
        if ($trend->count() >= 14) {
            $firstWeek = $trend->take(7)->sum('total');
            $lastWeek = $trend->reverse()->take(7)->sum('total');

            if ($firstWeek > 0) {
                if ($lastWeek > $firstWeek) {
                    $incremento = (($lastWeek - $firstWeek) / $firstWeek) * 100;
                    $message .= sprintf("\n✅ Tendencia POSITIVA: +%.1f%% en la última semana", $incremento);
                } else {
                    $decremento = (($firstWeek - $lastWeek) / $firstWeek) * 100;
                    $message .= sprintf("\n⚠️ Tendencia NEGATIVA: -%.1f%% en la última semana", $decremento);
                }
            }
        }

        return [
            'message' => $message,
            'data' => $trend,
            'type' => 'trend'
        ];
    }

    /**
     * Comparar ventas entre períodos
     */
    private function compareSales($temporalInfo)
    {
        $comparison = SalesAnalytics::compareSalesByDates($temporalInfo);

        $current = $comparison['periodo_actual'];
        $previous = $comparison['periodo_anterior'];

        if (!$current || !$previous || (!$current->total_ventas && !$previous->total_ventas)) {
            return [
                'message' => "No hay datos suficientes para realizar la comparación.",
                'data' => $comparison,
                'type' => 'comparison'
            ];
        }

        $periodDescription = $this->getComparisonDescription($temporalInfo);

        $message = sprintf(
            "🔄 Comparación %s:\n\n" .
            "%s:\n" .
            "• Ventas: %d\n" .
            "• Total: Bs. %.2f\n\n" .
            "%s:\n" .
            "• Ventas: %d\n" .
            "• Total: Bs. %.2f\n\n",
            $periodDescription['type'],
            $periodDescription['current'],
            $current->total_ventas ?? 0,
            $current->monto_total ?? 0,
            $periodDescription['previous'],
            $previous->total_ventas ?? 0,
            $previous->monto_total ?? 0
        );

        if ($previous->monto_total > 0) {
            if ($comparison['variacion_porcentual'] > 0) {
                $message .= sprintf(
                    "📈 Incremento del %.1f%% (Bs. %.2f más)",
                    $comparison['variacion_porcentual'],
                    $comparison['variacion_absoluta']
                );
            } elseif ($comparison['variacion_porcentual'] < 0) {
                $message .= sprintf(
                    "📉 Disminución del %.1f%% (Bs. %.2f menos)",
                    abs($comparison['variacion_porcentual']),
                    abs($comparison['variacion_absoluta'])
                );
            } else {
                $message .= "➡️ Las ventas se mantienen estables";
            }
        }

        return [
            'message' => $message,
            'data' => $comparison,
            'type' => 'comparison'
        ];
    }

    /**
     * Información general
     */
    private function getGeneralInfo()
    {
        return [
            'message' => "¡Hola! Soy tu asistente de ventas 🤖\n\n" .
                        "Puedo ayudarte con:\n\n" .
                        "📊 **Ventas**\n" .
                        "• La mejor venta del día/mes/año\n" .
                        "• Las mejores ventas (top 10)\n" .
                        "• Ventas de fechas específicas\n\n" .
                        "👤 **Vendedores**\n" .
                        "• El mejor vendedor\n" .
                        "• Los mejores vendedores\n" .
                        "• Quién vendió más en X período\n\n" .
                        "📦 **Productos**\n" .
                        "• Producto más vendido\n" .
                        "• Los productos más vendidos\n\n" .
                        "🏪 **Sucursales**\n" .
                        "• Rendimiento por sucursal\n" .
                        "• Comparación entre sucursales\n\n" .
                        "📈 **Análisis**\n" .
                        "• Resumen de ventas\n" .
                        "• Tendencias\n" .
                        "• Comparaciones\n\n" .
                        "💡 **Ejemplos de consultas**:\n" .
                        "• '¿Cuál fue la mejor venta del año 2024?'\n" .
                        "• '¿Cuáles son las ventas de enero de 2025?'\n" .
                        "• '¿Quién vendió más la semana pasada?'\n" .
                        "• 'Muéstrame los productos más vendidos de hoy'\n\n" .
                        "¿En qué puedo ayudarte?",
            'data' => null,
            'type' => 'help'
        ];
    }

    /**
     * Obtener descripción del período
     */
    private function getPeriodDescription($temporalInfo)
    {
        // Si hay fecha específica
        if ($temporalInfo['specific_date']) {
            return sprintf("del %s", Carbon::parse($temporalInfo['specific_date'])->format('d/m/Y'));
        }

        // Si hay año y mes específicos
        if ($temporalInfo['specific_year'] && $temporalInfo['specific_month']) {
            $monthNames = [
                1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
                5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
                9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
            ];
            return sprintf("de %s de %d", $monthNames[$temporalInfo['specific_month']], $temporalInfo['specific_year']);
        }

        // Si solo hay año específico
        if ($temporalInfo['specific_year']) {
            return sprintf("del año %d", $temporalInfo['specific_year']);
        }

        // Para períodos relativos
        $descriptions = [
            'day' => 'de hoy',
            'yesterday' => 'de ayer',
            'week' => 'de esta semana',
            'last_week' => 'de la semana pasada',
            'month' => 'de este mes',
            'last_month' => 'del mes pasado',
            'year' => 'de este año',
            'last_year' => 'del año pasado',
            'quarter' => 'de este trimestre',
            'last_7_days' => 'de los últimos 7 días',
            'last_30_days' => 'de los últimos 30 días',
            'last_90_days' => 'de los últimos 90 días',
            'all' => 'histórica (todo el período)'
        ];

        return $descriptions[$temporalInfo['period']] ?? 'del período';
    }

    /**
     * Obtener descripción para comparaciones
     */
    private function getComparisonDescription($temporalInfo)
    {
        if ($temporalInfo['specific_year'] && $temporalInfo['specific_month']) {
            $monthNames = [
                1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
                5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
                9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
            ];

            $currentMonth = $monthNames[$temporalInfo['specific_month']];
            $currentYear = $temporalInfo['specific_year'];

            // Calcular mes anterior
            $previousDate = Carbon::create($currentYear, $temporalInfo['specific_month'], 1)->subMonth();
            $previousMonth = $monthNames[$previousDate->month];
            $previousYear = $previousDate->year;

            return [
                'type' => 'mensual',
                'current' => sprintf("%s de %d", $currentMonth, $currentYear),
                'previous' => sprintf("%s de %d", $previousMonth, $previousYear)
            ];
        }

        if ($temporalInfo['specific_year']) {
            return [
                'type' => 'anual',
                'current' => sprintf("Año %d", $temporalInfo['specific_year']),
                'previous' => sprintf("Año %d", $temporalInfo['specific_year'] - 1)
            ];
        }

        $descriptions = [
            'month' => [
                'type' => 'mensual',
                'current' => 'Este mes',
                'previous' => 'Mes pasado'
            ],
            'week' => [
                'type' => 'semanal',
                'current' => 'Esta semana',
                'previous' => 'Semana pasada'
            ],
            'year' => [
                'type' => 'anual',
                'current' => 'Este año',
                'previous' => 'Año pasado'
            ],
            'quarter' => [
                'type' => 'trimestral',
                'current' => 'Este trimestre',
                'previous' => 'Trimestre pasado'
            ]
        ];

        return $descriptions[$temporalInfo['period']] ?? [
            'type' => 'de períodos',
            'current' => 'Período actual',
            'previous' => 'Período anterior'
        ];
    }

    /**
     * Endpoint para obtener métricas específicas
     */
    public function getMetrics(Request $request)
    {
        $request->validate([
            'metric' => 'required|string',
            'period' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'year' => 'nullable|integer|min:2020|max:2030',
            'month' => 'nullable|integer|min:1|max:12',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $metric = $request->metric;
        $limit = $request->limit ?? 10;

        // Construir información temporal desde los parámetros
        $temporalInfo = [
            'period' => $request->period ?? 'month',
            'specific_year' => $request->year,
            'specific_month' => $request->month,
            'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
            'end_date' => $request->end_date ? Carbon::parse($request->end_date) : null,
            'custom_range' => false
        ];

        // Si no hay fechas específicas, calcularlas
        if (!$temporalInfo['start_date'] || !$temporalInfo['end_date']) {
            $this->calculateDates($temporalInfo);
        }

        switch ($metric) {
            case 'top_sales':
                $data = SalesAnalytics::getTopSalesByDates(
                    $temporalInfo['start_date'],
                    $temporalInfo['end_date'],
                    $limit
                );
                break;
            case 'top_seller':
            case 'top_sellers':
                $data = SalesAnalytics::getTopSellersByDates(
                    $temporalInfo['start_date'],
                    $temporalInfo['end_date'],
                    $limit
                );
                break;
            case 'top_products':
                $data = SalesAnalytics::getTopProductsByDates(
                    $temporalInfo['start_date'],
                    $temporalInfo['end_date'],
                    $limit
                );
                break;
            case 'sales_by_branch':
                $data = SalesAnalytics::getSalesBySucursalByDates(
                    $temporalInfo['start_date'],
                    $temporalInfo['end_date']
                );
                break;
            case 'summary':
                $data = SalesAnalytics::getSalesSummary(
                    $temporalInfo['start_date'],
                    $temporalInfo['end_date']
                );
                break;
            case 'trend':
                $data = SalesAnalytics::getSalesTrendByDates(
                    $temporalInfo['start_date'] ?? Carbon::now()->subDays(30),
                    $temporalInfo['end_date'] ?? Carbon::now()
                );
                break;
            case 'comparison':
                $data = SalesAnalytics::compareSalesByDates($temporalInfo);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Métrica no válida. Métricas disponibles: top_sales, top_sellers, top_products, sales_by_branch, summary, trend, comparison'
                ], 400);
        }

        return response()->json([
            'success' => true,
            'metric' => $metric,
            'period' => $temporalInfo['period'],
            'temporal_info' => [
                'period' => $temporalInfo['period'],
                'start_date' => $temporalInfo['start_date'] ? $temporalInfo['start_date']->format('Y-m-d') : null,
                'end_date' => $temporalInfo['end_date'] ? $temporalInfo['end_date']->format('Y-m-d') : null,
                'description' => $this->getPeriodDescription($temporalInfo)
            ],
            'data' => $data
        ]);
    }
}