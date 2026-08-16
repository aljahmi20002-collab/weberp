<?php
namespace Modules\StockTransfer\Enums;
interface StockTransferStatus{
    const PENDING     = 0;
    const IN_TRANSIT  = 1;
    const COMPLETED   = 2;
}