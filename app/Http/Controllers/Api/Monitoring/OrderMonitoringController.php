<?php

namespace App\Http\Controllers\Api\Monitoring;

use App\Http\Controllers\Controller;
use App\Http\Requests\Monitoring\OrderMonitoringRequest;
use App\Services\Monitoring\OrderMonitoringService;
use App\Traits\ApiJsonResponceTrait;

class OrderMonitoringController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(protected OrderMonitoringService $orderService) {}

    public function index(OrderMonitoringRequest $request)
    {
        $orders = $this->orderService->loadAllGroups($request->getPage(), $request->getPerPage(), $request->getSupplierId());

        return $this->successResponse($orders);
    }
}
