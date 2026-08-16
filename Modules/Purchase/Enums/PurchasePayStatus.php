<?php
namespace Modules\Purchase\Enums;
interface PurchasePayStatus{
    const PAID     = 1;
    const PARTIAL  = 2;
    const DUE      = 3;
}