<?php

namespace App\Enums;

use InvalidArgumentException;

enum OrderStatusEnum: int
{
  case ACTIVE = 1;
  case CANCEL = 2;
  case DONE = 3;

  public function toString(): string
  {
    return match ($this) {
      self::ACTIVE => 'active',
      self::CANCEL => 'cancel',
      self::DONE => 'done',
    };
  }

  public static function fromString(string $status): self
  {
    return match ($status) {
      'active' => self::ACTIVE,
      'cancel' => self::CANCEL,
      'done' => self::DONE,
      default => throw new InvalidArgumentException("Invalid status: {$status}"),
    };
  }

  public static function getValidStrings(): array
  {
    return ['active', 'cancel', 'done'];
  }

  public function canTransitionTo(self $newStatus): bool
  {
    return match ($this) {
      self::ACTIVE => in_array($newStatus, [self::CANCEL, self::DONE]),
      self::CANCEL, self::DONE => false,
    };
  }
}
