<?php

namespace App\Enums;

use InvalidArgumentException;

enum OrderStatusEnum: int
{
  case ACTIVE = 1;
  case CANCELLED = 2;
  case COMPLETED = 3;

  public function toString(): string
  {
    return match ($this) {
      self::ACTIVE => 'active',
      self::CANCELLED => 'cancel',
      self::COMPLETED => 'done',
    };
  }

  public static function fromString(string $status): self
  {
    return match ($status) {
      'active' => self::ACTIVE,
      'cancel' => self::CANCELLED,
      'done' => self::COMPLETED,
      default => throw new InvalidArgumentException("Invalid status: {$status}"),
    };
  }

  public static function getValidStrings(): array
  {
    return ['active', 'cancel', 'done'];
  }
}
