<?php

namespace App\Repositories\SmsTemplate;

use App\Models\SmsTemplate;
use App\Repositories\Interfaces\SmsTemplateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SmsTemplateRepository implements SmsTemplateRepositoryInterface
{
  public function all(): Collection
  {
    return SmsTemplate::query()
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function findOrFail(int $id): SmsTemplate
  {
    return SmsTemplate::findOrFail($id);
  }

  public function create(array $data): SmsTemplate
  {
    return SmsTemplate::create($data);
  }

  public function update(int $id, array $data): SmsTemplate
  {
    $template = $this->findOrFail($id);
    $template->update($data);

    return $template->fresh();
  }

  public function delete(int $id): bool
  {
    $template = $this->findOrFail($id);

    return $template->delete();
  }

  public function findByType(string $type): Collection
  {
    return SmsTemplate::query()
      ->where('type', $type)
      ->where('status', true)
      ->get();
  }

  public function findActive(): Collection
  {
    return SmsTemplate::query()
      ->where('status', true)
      ->orderBy('created_at', 'desc')
      ->get();
  }
}
