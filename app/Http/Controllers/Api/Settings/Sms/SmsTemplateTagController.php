<?php

namespace App\Http\Controllers\Api\Settings\Sms;

use App\Enums\Sms\SmsTemplateTagEnum;
use App\Http\Controllers\Controller;
use App\Traits\ApiJsonResponceTrait;

class SmsTemplateTagController extends Controller
{
    use ApiJsonResponceTrait;

    public function getAll()
    {
        return $this->successResponse(
            SmsTemplateTagEnum::definitions()
        );
    }
}
