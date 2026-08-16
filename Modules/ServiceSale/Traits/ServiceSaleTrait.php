<?php
namespace Modules\ServiceSale\Traits;

use Illuminate\Support\Facades\Config;
use Modules\Customer\Enums\CustomerType;
use Modules\Sell\Enums\ShippingStatus;

trait ServiceSaleTrait
{
  
    public function customerTypes(){
        return collect([(object)[
            'id'   => CustomerType::WALK_CUSTOMER,
            'name' => __(Config::get('pos_default.customer_type.'.CustomerType::WALK_CUSTOMER))
        ],(object)[
            'id'   => CustomerType::EXISTING_CUSTOMER,
            'name' => __(Config::get('pos_default.customer_type.'.CustomerType::EXISTING_CUSTOMER))
        ]]);

         
    }

    public function ShippingStatusCollection(){
        $shipping_status = [];

        foreach (Config::get('pos_default.shpping_status') as $key => $name) {
            $shipping_status [] = (object)[
                'id'   => $key,
                'name' => __($name),
            ];
        }
        return collect($shipping_status);
    }
}
