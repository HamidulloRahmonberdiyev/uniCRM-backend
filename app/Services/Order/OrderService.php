<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Repositories\Interfaces\NeighborhoodRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private NeighborhoodRepositoryInterface $neighborhoodRepository
    ) {}

    public function getAllOrders(array $data)
    {
        $query = Order::query()
            ->select([
                'id',
                'customer_id',
                'user_id',
                'company_id',
                'source_id',
                'date',
                'status',
                'sum',
                'district_id',
                'neighborhood_id',
                'address',
                'quantity',
                'note',
                'latitude',
                'longitude',
                'created_at'
            ]);

        $this->applyFilters($query, $data);

        $query->with([
            'customer:id,first_name,last_name,middle_name,phone,phone2',
            'district:id,name',
            'neighborhood:id,name',
            'source:id,name',
        ]);

        return $query->orderByDesc('created_at')->paginate(20);
    }

    public function createOrder(array $data): Order
    {
        if (!empty($data['neighborhood_id'])) {
            $neighborhood = $this->neighborhoodRepository->findById($data['neighborhood_id']);
            $data['district_id'] = $neighborhood?->district_id;
        }

        $orderData = $this->prepareOrderCreateData($data);

        return $this->orderRepository->create($orderData);
    }

    private function prepareOrderCreateData(array $data): array
    {
        return [
            'customer_id' => $data['customer_id'],
            'user_id' => Auth::id(),
            'company_id' => $data['company_id'] ?? 1,
            'district_id' => $data['district_id'] ?? null,
            'neighborhood_id' => $data['neighborhood_id'] ?? null,
            'quantity' => $data['quantity'],
            'sum' => $data['sum'] ?? null,
            'date' => $data['date'] ?? Carbon::today(),
            'address' => $data['address'] ?? null,
            'note' => $data['note'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'status' => $data['status'] ?? Order::ACTIVE,
            'source_id' => $data['source_id'] ?? 1,
            'supplier_id' => $data['supplier_id'] ?? null,
            'product_id' => $data['product_id'] ?? null,
        ];
    }

    public function updateOrder(Order $order, array $data): Order
    {
        if (!empty($data['neighborhood_id'])) {
            $neighborhood = $this->neighborhoodRepository->findById($data['neighborhood_id']);
            $data['district_id'] = $neighborhood?->district_id;
        }

        $updateData = $this->prepareOrderUpdateData($order, $data);

        return $this->orderRepository->update($order, $updateData);
    }

    private function prepareOrderUpdateData(Order $order, array $data): array
    {
        return [
            'customer_id' => $order->customer_id,
            'district_id' => $data['district_id'] ?? $order->district_id,
            'neighborhood_id' => $data['neighborhood_id'] ?? $order->neighborhood_id,
            'product_id' => $data['product_id'] ?? $order->product_id,
            'quantity' => $data['quantity'] ?? $order->quantity,
            'sum' => $data['sum'] ?? $order->sum,
            'address' => $data['address'] ?? $order->address,
            'note' => $data['note'] ?? $order->note,
            'status' => $data['status'] ?? $order->status,
        ];
    }

    public function deleteOrder(Order $order): void
    {
        $order->delete();
    }

    public function changeStatusOrder(Order $order, $data)
    {
        return $order->update([
            'status' => $data
        ]);
    }

    public function getOrderStats(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date|after_or_equal:start',
        ]);

        $query = Order::query();
        if ($request->start && $request->end) {
            $query->whereBetween('date', [$request->start, $request->end]);
        }

        return [
            'total' => [
                'orders' => $query->count(),
                'bottles' => $query->sum('quantity')
            ],
            'done' => [
                'orders' => (clone $query)->where('status', Order::DONE)->count(),
                'bottles' => (clone $query)->where('status', Order::DONE)->sum('quantity')
            ],
            'active' => [
                'orders' => (clone $query)->where('status', Order::ACTIVE)->count(),
                'bottles' => (clone $query)->where('status', Order::ACTIVE)->sum('quantity')
            ],
            'canceled' => [
                'orders' => (clone $query)->where('status', Order::CANCEL)->count(),
                'bottles' => (clone $query)->where('status', Order::CANCEL)->sum('quantity')
            ]
        ];
    }

    private function applyFilters($query, array $data)
    {
        $nameOrPhone = !empty($data['name_or_phone']) ? extractNameAndPhone($data['name_or_phone']) : ['name' => '', 'phone' => ''];
        $name = $nameOrPhone['name'] ?? '';
        $phone = $nameOrPhone['phone'] ?? '';

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['start_date']) && isset($data['end_date'])) {
            $query->whereBetween('date', [$data['start_date'], $data['end_date']]);
        }

        if (isset($name)) {
            $search = '%' . $name . '%';
            $query->whereIn('customer_id', function ($subQuery) use ($search) {
                $subQuery->select('id')
                    ->from('customers')
                    ->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('middle_name', 'like', $search);
            });
        }

        if (isset($phone)) {
            $search = '%' . $phone . '%';
            $query->whereIn('customer_id', function ($subQuery) use ($search) {
                $subQuery->select('id')
                    ->from('customers')
                    ->where('phone', 'like', $search)
                    ->orWhere('phone2', 'like', $search);
            });
        }
    }

    public function changeStatus(Order $order, array $data)
    {
        $status = OrderStatusEnum::fromString($data['status']);

        return $order->update([
            'supplier_id' => $data['supplier_id'] ?? null,
            'status' => $status->value,
        ]);
    }
}
