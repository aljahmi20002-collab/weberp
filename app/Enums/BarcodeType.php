<?php
namespace App\Enums;
interface BarcodeType{
    const C128  = 1;
    const C39   = 2;
    const EAN13 = 3;
    const EAN8  = 4;
    const UPCA  = 5;
    const UPCE  = 6;
}