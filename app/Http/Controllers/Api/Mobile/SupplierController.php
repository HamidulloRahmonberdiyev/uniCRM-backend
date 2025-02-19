<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function supplierStats()
    {
        $activesCount = Order::where('status', Order::ACTIVE)->where('supplier_id', Auth::id());

        $deliveredCount = Order::where('status', Order::DONE)->where('supplier_id', Auth::id());

        return [
            'active_orders' => $activesCount,
            'delivered_orders' => $deliveredCount,
        ];
    }
}
