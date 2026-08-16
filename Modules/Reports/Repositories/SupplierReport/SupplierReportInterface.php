<?php
namespace Modules\Reports\Repositories\SupplierReport;
Interface SupplierReportInterface {
    public function getPurchaseReturnReport($request);
    public function getPurchaseReport($request);
}