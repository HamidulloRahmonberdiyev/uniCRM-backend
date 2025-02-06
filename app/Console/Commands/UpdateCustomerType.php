<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCustomerType extends Command
{
    protected $signature = 'update:customer-type';

    protected $description = 'Update the customer status based on days since last order';

    public function handle()
    {
        $customers = Customer::select('customers.*')
            ->addSelect(DB::raw('COALESCE(DATEDIFF(CURRENT_DATE, MAX(orders.date)), DATEDIFF(CURRENT_DATE, customers.created_at)) as days_since_last_order'))
            ->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->groupBy('customers.id')
            ->get();

        foreach ($customers as $customer) {
            if ($customer->days_since_last_order <= 7 && $customer->days_since_last_order !== 0) {
                $customer->type_id = 1;
            } elseif ($customer->days_since_last_order <= 10 && $customer->days_since_last_order !== 0) {
                $customer->type_id = 2;
            } elseif ($customer->days_since_last_order > 10) {
                $customer->type_id = 3;
            } else {
                $customer->type_id = 4;
            }

            $customer->save();
        }

        $this->info('Customer statuses have been updated successfully.');
    }
}
