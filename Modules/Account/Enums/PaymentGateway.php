<?php
namespace Modules\Account\Enums;
interface PaymentGateway{
    const CASH    = 1;
    const BANK    = 2;
    const MOBILE  = 3;
}