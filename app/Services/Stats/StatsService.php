<?php

namespace App\Services\Stats;

use App\Models\CustomerType;
use App\Models\Order;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsService
{
    public function getMonthlyOrderStats($year)
    {
        return Order::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(quantity) as total_quantity')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($order) use ($year) {
                $monthName = Carbon::parse($order->month . '-01')->translatedFormat('F');
                return [
                    'year' => intval($year),
                    'month' => getUzbekMonth($monthName),
                    'value' => intval($order->total_quantity),
                ];
            });
    }

    public function getCustomerTypeChart()
    {
        return CustomerType::query()
            ->select('label')
            ->withCount('customers')
            ->get()
            ->map(function ($type) {
                return [
                    'label' => $type->label,
                    'value' => $type->customers_count,
                ];
            });
    }

    public function getOrderSourceChart($year)
    {
        $orders = Order::query()
            ->join('sources', 'orders.source_id', '=', 'sources.id')
            ->select(
                DB::raw('MONTH(orders.created_at) as month'),
                'sources.name as source',
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('orders.created_at', $year)
            ->groupBy('month', 'source')
            ->orderBy('month')
            ->get();

        $months = [];
        $sources = [];
        $data = [];

        foreach ($orders as $order) {
            if (!in_array($order->month, $months)) {
                $months[] = $order->month;
            }

            if (!in_array($order->source, $sources)) {
                $sources[] = $order->source;
            }
        }

        foreach ($months as $month) {
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            $data[$monthName] = [];

            foreach ($sources as $source) {
                $data[$monthName][$source] = 0;
            }
        }

        foreach ($orders as $order) {
            $monthName = date('F', mktime(0, 0, 0, $order->month, 1));
            $data[$monthName][$order->source] = $order->count;
        }

        $result = [];
        foreach ($data as $month => $sourceData) {
            $item = ['month' => getUzbekMonth($month)];
            foreach ($sourceData as $source => $count) {
                $item[$source] = $count;
            }
            $result[] = $item;
        }

        return [
            'data' => $result,
            'sources' => $sources
        ];
    }

    public function getSupplierOrdersChart($month)
    {
        $supplierOrders = Order::select('supplier_id')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('supplier_id')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('supplier_id')
            ->with('supplier:id,name')
            ->get();

        return $supplierOrders->map(function ($item) {
            return [
                'supplier_name' => $item->supplier->name,
                'order_count' => $item->order_count
            ];
        });
    }
}
