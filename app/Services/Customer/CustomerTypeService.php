<?php

namespace App\Services\Customer;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerTypeService
{
  public function __construct(private Customer $customers) {}

  public function getFilteredCustomersForSms(array $filters)
  {
    return Customer::query()
      ->when(
        $filters['customer_type_id'] ?? null,
        fn(Builder $query, int $typeId) =>
        $query->where('type_id', $typeId)
      )
      ->when(
        $filters['district_id'] ?? null,
        fn(Builder $query, int $districtId) =>
        $query->whereRelation('customerDetail', 'district_id', $districtId)
      )
      ->when(
        $filters['neighborhood_id'] ?? null,
        fn(Builder $query, int $neighborhoodId) =>
        $query->whereRelation('customerDetail', 'neighborhood_id', $neighborhoodId)
      )
      ->active()
      ->limit($filters['limit'] ?? 1000)
      ->get();
  }
}
