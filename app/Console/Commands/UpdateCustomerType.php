<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\CustomerType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCustomerType extends Command
{
    protected $signature = 'update:customer-type';

    protected $description = 'Update the customer status based on days since last order';

    public function handle()
    {
        $customerTypes = CustomerType::orderBy('sortable')->get();

        $passiveType = CustomerType::orderBy('sortable', 'desc')->first();

        $customers = Customer::select('customers.*')
            ->addSelect(DB::raw('COALESCE(DATEDIFF(CURRENT_DATE, MAX(orders.date)), DATEDIFF(CURRENT_DATE, customers.created_at)) as days_since_last_order'))
            ->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->groupBy('customers.id')
            ->get();

        foreach ($customers as $customer) {
            foreach ($customerTypes as $type) {
                if ($type->number != 0) {
                    if ($customer->days_since_last_order <= $type->number) {
                        $customer->type_id = $type->id;
                        break;
                    } else {
                        $customer->type_id = $passiveType->id;
                    }
                }
            }
            $customer->save();
        }

        $this->info('Customer type have been updated successfully.');
    }
}
