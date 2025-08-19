<?php

namespace App\Services\Monitoring;

use App\Enums\OrderGroupType;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiJsonResponceTrait;
use Illuminate\Support\Facades\DB;

class OrderMonitoringService
{
    use ApiJsonResponceTrait;

    public function loadAllGroups(int $page, int $perPage, ?int $supplierId = null)
    {
        $result = [];

        foreach (OrderGroupType::cases() as $groupType) {
            [$query, $count] = $this->getQueryForGroup($groupType, $supplierId);

            $orders = $query->orderBy('updated_at', 'desc')
                ->forPage($page, $perPage)
                ->get();

            $result[$groupType->value] = [
                'data' => OrderResource::collection($orders),
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $count,
                    'last_page' => ceil($count / $perPage)
                ]
            ];
        }

        return $result;
    }

    private function getQueryForGroup(OrderGroupType $groupType, ?int $supplierId = null): array
    {
        $query = Order::query();
        $countQuery = DB::table('orders');

        if ($supplierId !== null) {
            $query->where('supplier_id', $supplierId);
            $countQuery->where('supplier_id', $supplierId);
        }

        $conditions = $groupType->getConditions();

        $query->where('status', $conditions['status']);
        $countQuery->where('status', $conditions['status']);

        switch ($conditions['supplier_condition']) {
            case 'null':
                $query->whereNull('supplier_id');
                $countQuery->whereNull('supplier_id');
                break;
            case 'not_null':
                $query->whereNotNull('supplier_id');
                $countQuery->whereNotNull('supplier_id');
                if ($groupType === OrderGroupType::DELIVERING) {
                    $query->with('supplier');
                }
                break;
            case 'any':
                break;
        }

        $count = $countQuery->count();

        return [$query, $count];
    }
}
