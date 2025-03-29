<?php

namespace App\Services\Customer;

use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use App\Models\Neighborhood;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerService
{
    public function getAllCustomers(array $data)
    {
        $nameOrPhone = !empty($data['name_or_phone']) ? extractNameAndPhone($data['name_or_phone']) : ['name' => '', 'phone' => ''];
        $name = $nameOrPhone['name'] ?? '';
        $phone = $nameOrPhone['phone'] ?? '';

        $query = Customer::where('status', Customer::ACTIVE);

        if (isset($data['type_id']) && !empty($data['type_id'])) {
            $query->where('type_id', $data['type_id']);
        }

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'LIKE', "%{$name}%")
                    ->orWhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhere('middle_name', 'LIKE', "%{$name}%");
            });
        }

        if ($phone) {
            $query->where(function ($q) use ($phone) {
                $q->where('phone', 'LIKE', "%{$phone}%")
                    ->orWhere('phone2', 'LIKE', "%{$phone}%");
            });
        }

        return $query->join('customer_types', 'customers.type_id', '=', 'customer_types.id')
            ->orderBy('customer_types.sortable')
            ->select('customers.*')
            ->paginate(20);
    }

    public function createCustomer(array $data): Customer
    {
        return DB::transaction(function () use ($data) {

            if (!empty($data['customer_detail'])) {
                $data['customer_detail'] = $this->getRegionIdFromLocation($data['customer_detail']);
            }

            $customer = Customer::create([
                'user_id'      => Auth::id(),
                'company_id'   => 1,
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'] ?? null,
                'middle_name'  => $data['middle_name'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'phone'        => sanitizePhone($data['phone']),
                'phone2'       => sanitizePhone($data['phone2'] ?? null),
                'status'       => Customer::ACTIVE,
            ]);

            if (!empty($data['customer_detail'])) {
                $customer->customerDetail()->create([
                    'region_id'      => $data['customer_detail']['region_id'] ?? null,
                    'district_id'    => $data['customer_detail']['district_id'] ?? null,
                    'neighborhood_id' => $data['customer_detail']['neighborhood_id'] ?? null,
                    'home'           => $data['customer_detail']['home'] ?? null,
                    'bottle_count'   => $data['customer_detail']['bottle_count'] ?? null,
                ]);
            }

            return $customer;
        });
    }

    public function updateCustomer(Customer $customer, array $data): Customer
    {
        return DB::transaction(function () use ($customer, $data) {

            if (!empty($data['customer_detail'])) {
                $data['customer_detail'] = $this->getRegionIdFromLocation($data['customer_detail']);
            }

            $customer->update([
                'first_name'    => $data['first_name'] ?? $customer->first_name,
                'last_name'     => $data['last_name'] ?? $customer->last_name,
                'middle_name'   => $data['middle_name'] ?? $customer->middle_name,
                'date_of_birth' => $data['date_of_birth'] ?? $customer->date_of_birth,
                'phone'         => sanitizePhone($data['phone']) ?? $customer->phone,
                'phone2'        => isset($data['phone2']) ? sanitizePhone($data['phone2']) : $customer->phone2,
                'status'        => $data['status'] ?? $customer->status,
            ]);

            if (!empty($data['customer_detail'])) {
                $customer->customerDetail()->updateOrCreate(
                    ['customer_id' => $customer->id],
                    [
                        'region_id'       => $data['customer_detail']['region_id'] ?? null,
                        'district_id'     => $data['customer_detail']['district_id'] ?? null,
                        'neighborhood_id' => $data['customer_detail']['neighborhood_id'] ?? null,
                        'home'            => $data['customer_detail']['home'] ?? null,
                        'bottle_count'    => $data['customer_detail']['bottle_count'] ?? null,
                    ]
                );
            }

            return $customer;
        });
    }

    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }

    public function getOrderHistory(Customer $customer, array $data)
    {
        $query = $customer->orders();

        if (!empty($data['start_date'])) {
            $startDate = Carbon::parse($data['start_date'])->startOfDay();
            $query->where('date', '>=', $startDate);
        }

        if (!empty($data['end_date'])) {
            $endDate = Carbon::parse($data['end_date'])->endOfDay();
            $query->where('date', '<=', $endDate);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getCustomerStats()
    {
        $stats = Customer::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN type_id = 1 THEN 1 ELSE 0 END) as active')
            ->selectRaw('SUM(CASE WHEN type_id = 2 THEN 1 ELSE 0 END) as normal')
            ->selectRaw('SUM(CASE WHEN type_id = 3 THEN 1 ELSE 0 END) as passive')
            ->first();

        return [
            'total' => $stats->total,
            'active' => $stats->active,
            'normal' => $stats->normal,
            'passive' => $stats->passive
        ];
    }

    protected function getRegionIdFromLocation(array $locationData): array
    {
        if (!empty($locationData['neighborhood_id'])) {
            $neighborhood = Neighborhood::with('district.region')
                ->find($locationData['neighborhood_id']);

            if ($neighborhood) {
                $locationData['district_id'] = $neighborhood->district_id;

                if (!empty($neighborhood->district_id) && !empty($neighborhood->district->region_id)) {
                    $locationData['region_id'] = $neighborhood->district->region_id;
                }
            }
        }

        if (empty($locationData['region_id']) && !empty($locationData['district_id'])) {
            $district = District::with('region')->find($locationData['district_id']);
            if ($district && !empty($district->region_id)) {
                $locationData['region_id'] = $district->region_id;
            }
        }

        return $locationData;
    }

    public function searchCustomer(array $data)
    {
        $nameOrPhone = !empty($data['name_or_phone']) ? extractNameAndPhone($data['name_or_phone']) : ['name' => '', 'phone' => ''];
        $name = $nameOrPhone['name'] ?? '';
        $phone = $nameOrPhone['phone'] ?? '';

        $query = Customer::query();

        if ($name || $phone) {
            $query->where(function ($q) use ($name, $phone) {
                if ($name) {
                    $q->where('first_name', 'LIKE', "%{$name}%")
                        ->orWhere('last_name', 'LIKE', "%{$name}%");
                }
                if ($phone) {
                    $q->orWhere('phone', 'LIKE', "%{$phone}%")
                        ->orWhere('phone2', 'LIKE', "%{$phone}%");
                }
            });
        }

        return $query->orderByRaw(" 
    CASE  
        WHEN phone = ? OR phone2 = ? THEN 1 -- Exact phone match 
        WHEN phone LIKE ? OR phone2 LIKE ? THEN 2 -- Phone starts with the pattern
        WHEN phone LIKE ? OR phone2 LIKE ? THEN 3 -- Phone contains the pattern
        WHEN first_name = ? OR last_name = ? THEN 4 -- Exact name match
        WHEN first_name LIKE ? OR last_name LIKE ? THEN 5 -- Name starts with the pattern
        WHEN first_name LIKE ? OR last_name LIKE ? THEN 6 -- Name contains the pattern
        ELSE 7 
    END, 
    CASE  
        WHEN (phone LIKE ? OR phone2 LIKE ?) AND (first_name LIKE ? OR last_name LIKE ?) THEN 1 -- Both phone and name match
        ELSE 2 -- Single field match
    END,
    first_name ASC", [
            $phone,
            $phone,
            $phone . '%',
            $phone . '%',
            '%' . $phone . '%',
            '%' . $phone . '%',
            $name,
            $name,
            $name . '%',
            $name . '%',
            '%' . $name . '%',
            '%' . $name . '%',
            '%' . $phone . '%',
            '%' . $phone . '%',
            '%' . $name . '%',
            '%' . $name . '%'
        ])
            ->limit(20)
            ->get();
    }
}
