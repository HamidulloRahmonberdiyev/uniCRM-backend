<?php

namespace App\Services\Sms;

use App\Enums\Sms\SmsTemplateTagEnum;
use App\Models\Customer;
use App\Models\Order;

class SmsTemplateParserService
{
  public function parse(string $content, Customer $customer, ?Order $order = null): string
  {
    $data = $this->getData($customer, $order);

    $content = preg_replace_callback(
      '/\{(\w+)\}/',
      fn($match) => $data[strtolower($match[1])] ?? $match[0],
      $content
    );

    $content = preg_replace_callback(
      '/&(\w+)&/',
      fn($match) => $data[strtolower($match[1])] ?? $match[0],
      $content
    );

    return $content;
  }

  private function getData(Customer $customer, ?Order $order): array
  {
    return array_filter([
      SmsTemplateTagEnum::CUSTOMER_NAME->value  => $customer->full_name,
      SmsTemplateTagEnum::CUSTOMER_PHONE->value => formatPhone($customer->phone),
      SmsTemplateTagEnum::COMPANY_NAME->value   => $customer->company?->name,
      SmsTemplateTagEnum::COMPANY_PHONE->value  => formatPhone($customer->company?->phone),
      SmsTemplateTagEnum::ORDER_PRICE->value    => $order?->price ? formatPrice($order->price) : null,
      SmsTemplateTagEnum::ORDER_DATE->value     => $order?->created_at?->format('d.m.Y H:i'),
    ]);
  }

  public function hasOrderTags(string $content): bool
  {
    return str_contains($content, '{order_price}') ||
      str_contains($content, '{order_date}') ||
      str_contains($content, '&order_price&') ||
      str_contains($content, '&order_date&');
  }
}
