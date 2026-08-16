<?php
namespace Modules\Reports\Repositories\StockReport;
interface StockReportInterface {
    public function getReport($request);
    public function getTotalCalculation($ProductVariations);
}