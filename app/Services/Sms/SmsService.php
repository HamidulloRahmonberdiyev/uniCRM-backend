<?php

namespace App\Services\Sms;

use App\Models\Customer;
use App\Models\Order;
use App\Models\SmsTemplate;

class SmsService
{
  public function __construct(
    private readonly SmsTemplateParserService $parser,
    private readonly SmsTemplateService $templateService,
    private readonly EskizService $eskizService,
  ) {}

  public function sendFromTemplate(SmsTemplate $template, Customer $customer, ?Order $order = null): string
  {
    abort_unless($template->status, 400, 'Shablon faol emas');
    abort_if($this->parser->hasOrderTags($template->content) && !$order, 400, 'Buyurtma kerak');

    return $this->parser->parse($template->content, $customer, $order);
  }

  public function prepareRecipients($customers, SmsTemplate $template): array
  {
    return $customers->map(fn(Customer $customer) => [
      'phone' => $customer->phone,
      'message' => $this->sendFromTemplate($template, $customer),
    ])->toArray();
  }

  public function sendSms($customers, int $templateId): void
  {
    $template = $this->templateService->findOrFail($templateId);
    $recipients = $this->prepareRecipients($customers, $template);

    $this->eskizService->sendMany($recipients);
  }
}
