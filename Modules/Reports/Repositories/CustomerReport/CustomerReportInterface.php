<?php
namespace Modules\Reports\Repositories\CustomerReport;
interface CustomerReportInterface {
    public function getReport($request); 
    public function getSales($request); 
    public function getCustomerName($request);
    public function getBranchName($request);
}