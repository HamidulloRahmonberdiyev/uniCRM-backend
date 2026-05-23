<?php

namespace App\Http\Controllers\Api\Settings\Sms;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerType\FilterCustomerTypeRequest;
use App\Http\Requests\Settings\Sms\Eskiz\EskizSendRequest;
use App\Models\Customer;
use App\Services\Customer\CustomerTypeService;
use App\Services\Sms\EskizService;
use App\Services\Sms\SmsService;
use App\Services\Sms\SmsTemplateService;
use App\Traits\ApiJsonResponceTrait;

class EskizController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(
        private EskizService $eskizService,
        private SmsService $smsService,
        private SmsTemplateService $templateService,
        private CustomerTypeService $customerTypeService
    ) {}

    public function send(EskizSendRequest $request)
    {
        $this->smsService->sendSms(
            Customer::findMany($request->customer_ids),
            $request->template_id
        );

        return $this->successResponse(['sent_count' => count($request->customer_ids)]);
    }

    public function sendByFilter(FilterCustomerTypeRequest $request)
    {
        $customers = $this->customerTypeService->getFilteredCustomersForSms($request->validated());

        $this->smsService->sendSms($customers, $request->template_id);

        return $this->successResponse(['sent_count' => $customers->count()]);
    }
}
