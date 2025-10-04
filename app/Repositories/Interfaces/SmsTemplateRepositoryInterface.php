<?php

namespace App\Repositories\Interfaces;

use App\Models\SmsTemplate;
use Illuminate\Database\Eloquent\Collection;

interface SmsTemplateRepositoryInterface
{
  public function all(): Collection;

  public function findOrFail(int $id): SmsTemplate;

  public function create(array $data): SmsTemplate;

  public function update(int $id, array $data): SmsTemplate;

  public function delete(int $id): bool;

  public function findByType(string $type): Collection;

  public function findActive(): Collection;
}
