<?php

namespace App\Enums\Sms;

enum SmsTemplateTypeEnum: string
{
  case DEBT    = 'debt';
  case ORDER   = 'order';
  case MESSAGE = 'message';
  case NOTICE  = 'notice';

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
      self::DEBT->value    => 'Qarzdorlik',
      self::ORDER->value   => 'Buyurtma',
      self::MESSAGE->value => 'Xabar',
      self::NOTICE->value  => 'Eslatma',
    ];
  }

  public static function definitions(): array
  {
    return array_map(
      fn(self $case): array => [
        'value' => $case->value,
        'label'    => self::labels()[$case->value] ?? $case->value,
      ],
      self::cases()
    );
  }
}
