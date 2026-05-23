<?php

namespace App\Services\Sms;

use App\Models\SmsTemplate;
use App\Repositories\SmsTemplate\SmsTemplateRepository;
use Illuminate\Database\Eloquent\Collection;

class SmsTemplateService
{
  public function __construct(
    private readonly SmsTemplateRepository $repository
  ) {}

  public function getAll(): Collection
  {
    return $this->repository->all();
  }

  public function findOrFail(int $id): SmsTemplate
  {
    return $this->repository->findOrFail($id);
  }

  public function create(array $data): SmsTemplate
  {
    return $this->repository->create($data);
  }

  public function update(int $id, array $data): SmsTemplate
  {
    return $this->repository->update($id, $data);
  }

  public function delete(int $id): bool
  {
    return $this->repository->delete($id);
  }
}
