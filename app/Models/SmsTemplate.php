<?php

namespace App\Models;

use App\Enums\Sms\SmsTemplateTypeEnum;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'content',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => SmsTemplateTypeEnum::class
    ];
}
