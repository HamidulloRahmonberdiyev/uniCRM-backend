<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stats\ChartRequest;
use App\Models\Customer;
use App\Models\CustomerType;
use App\Services\Stats\StatsService;
use App\Traits\ApiJsonResponceTrait;

class StatsController extends Controller
{
    use ApiJsonResponceTrait;

    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }

    public function monthlyOrderChart(ChartRequest $request)
    {
        $orders = $this->statsService->getMonthlyOrderStats($request->getYear());

        return $this->successResponse($orders, 'Monthly orders chart');
    }

    public function customerTypeChart()
    {
        $customerTypes = $this->statsService->getCustomerTypeChart();

        return $this->successResponse($customerTypes, 'Customer types chart');
    }

    public function orderSourceChart(ChartRequest $request)
    {
        $orderSources = $this->statsService->getOrderSourceChart($request->getYear());

        return $this->successResponse($orderSources, 'Order sources chart');
    }

    public function supplierOrdersChart(ChartRequest $request)
    {
        $supplierOrders = $this->statsService->getSupplierOrdersChart($request->getMonth());

        return $this->successResponse($supplierOrders, 'supplier orders chart');
    }
}
