<?php
namespace Modules\Sell\Enums;
interface  ShippingStatus {
    const ORDERED   = 1;
    const PACKED    = 2;
    const SHIPPED   = 3;
    const DELIVERED = 4;
    const CANCELLED = 5;
}