<?php

namespace App\Services\Mobile;

use App\Models\Order;

class OrderService
{
    public function getActiveOrders(array $data)
    {
        $nameOrPhone = !empty($data['name_or_phone']) ? extractNameAndPhone($data['name_or_phone']) : ['name' => '', 'phone' => ''];

        $name = $nameOrPhone['name'] ?? '';
        $phone = $nameOrPhone['phone'] ?? '';

        $orders = Order::with(['customer', 'city', 'district', 'neighborhood'])
            ->where('supplier_id', null)
            ->where('status', Order::ACTIVE)
            ->whereHas('customer', function ($q) use ($name, $phone) {
                if (!empty($name)) {
                    $q->where('first_name', 'like', "%{$name}%")
                        ->orWhere('last_name', 'like', "%{$name}%")
                        ->orWhere('middle_name', 'like', "%{$name}%");
                }

                if (!empty($phone)) {
                    $q->orWhere('phone', 'like', "%{$phone}%")
                        ->orWhere('phone2', 'like', "%{$phone}%");
                }
            })
            ->orderByRaw("
            (CASE 
                WHEN EXISTS (
                    SELECT 1 FROM customers 
                    WHERE customers.id = orders.customer_id 
                    AND (customers.phone LIKE ? 
                         OR customers.phone2 LIKE ?)
                ) THEN 1
                WHEN EXISTS (
                    SELECT 1 FROM customers 
                    WHERE customers.id = orders.customer_id 
                    AND (customers.first_name LIKE ? 
                         OR customers.last_name LIKE ? 
                         OR customers.middle_name LIKE ?)
                ) THEN 2
                ELSE 3
            END)", ["%{$phone}%", "%{$phone}%", "%{$name}%", "%{$name}%", "%{$name}%"])
            ->get();

        return $orders;
    }
}
