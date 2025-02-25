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
            ->addSelect(DB::raw('
                COALESCE(
                    DATEDIFF(CURRENT_DATE, (SELECT MAX(o.date) FROM orders o WHERE o.customer_id = customers.id)),
                    DATEDIFF(CURRENT_DATE, customers.created_at)
                ) as days_since_last_order
            '))
            ->get();

        foreach ($customers as $customer) {
            $customerTypeAssigned = false;

            foreach ($customerTypes as $type) {
                if ($type->number != 0 && $customer->days_since_last_order <= $type->number) {
                    $customer->type_id = $type->id;
                    $customerTypeAssigned = true;
                    break;
                }
            }

            if (!$customerTypeAssigned) {
                $customer->type_id = $passiveType->id;
            }

            $customer->save();
        }

        $this->info('Customer types have been updated successfully.');
    }
}
