<?php

namespace App\Enums\Sms;

enum SmsTemplateTagEnum: string
{
  case ORDER_PRICE = 'order_price';
  case ORDER_DATE = 'order_date';
  case COMPANY_NAME = 'company_name';
  case COMPANY_PHONE = 'company_phone';
  case CUSTOMER_NAME = 'customer_name';
  case CUSTOMER_PHONE = 'customer_PHONE';

  public static function values(): array
  {
    return array_map(
      fn(self $case): string => $case->value,
      self::cases()
    );
  }

  public static function labels(): array
  {
    return [
      self::ORDER_PRICE->value    => 'Buyurtma narxi',
      self::ORDER_DATE->value     => 'Buyurtma sanasi',
      self::COMPANY_NAME->value   => 'Korxona nomi',
      self::COMPANY_PHONE->value  => 'Korxona telefon raqami',
      self::CUSTOMER_NAME->value  => 'Mijoz ismi',
      self::CUSTOMER_PHONE->value => 'Mijoz telefon raqami',
    ];
  }

  public function placeholder(): string
  {
    return "{{$this->value}}";
  }

  public static function definitions(): array
  {
    return array_map(
      fn(self $case): array => [
        'variable' => $case->placeholder(),
        'label'    => self::labels()[$case->value] ?? $case->value,
      ],
      self::cases()
    );
  }
}
