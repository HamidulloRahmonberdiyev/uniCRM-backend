<?php

namespace App\Http\Controllers\Api\Settings\Sms;

use App\Enums\Sms\SmsTemplateTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Sms\SmsTemplate\StoreSmsTemplateRequest;
use App\Http\Requests\Settings\Sms\SmsTemplate\UpdateSmsTemplateRequest;
use App\Http\Resources\Sms\SmsTemplateResource;
use App\Models\SmsTemplate;
use App\Services\Sms\SmsTemplateService;
use App\Traits\ApiJsonResponceTrait;

class SmsTemplateController extends Controller
{
    use ApiJsonResponceTrait;

    public function __construct(
        private readonly SmsTemplateService $service
    ) {}

    public function index()
    {
        $templates = $this->service->getAll();

        return $this->successResponse(
            SmsTemplateResource::collection($templates)
        );
    }

    public function store(StoreSmsTemplateRequest $request)
    {
        $template = $this->service->create($request->validated());

        return $this->successResponse(
            new SmsTemplateResource($template)
        );
    }

    public function show(SmsTemplate $template)
    {
        return $this->successResponse(new SmsTemplateResource($template));
    }

    public function update(UpdateSmsTemplateRequest $request, SmsTemplate $template)
    {
        $template = $this->service->update($template->id, $request->validated());

        return $this->successResponse(
            new SmsTemplateResource($template)
        );
    }

    public function destroy(SmsTemplate $template)
    {
        $this->service->delete($template->id);

        return $this->successResponse(null);
    }

    public function getTemplateTypes()
    {
        return $this->successResponse(
            SmsTemplateTypeEnum::definitions()
        );
    }
}
