<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function supplierStats()
    {
        $activesCount = Order::where('status', Order::ACTIVE)->where('supplier_id', Auth::id())->count();

        $deliveredCount = Order::where('supplier_id', Auth::id())->where('status', Order::DONE)->count();

        return [
            'active_orders' => $activesCount,
            'delivered_orders' => $deliveredCount,
        ];
    }
}
