<?php
namespace Modules\Pos\Repositories\CustomerPosReport;
interface CustomerPosReportInterface {
    public function getReport($request); 
    public function getSales($request); 
    public function getCustomerName($request);
    public function getBranchName($request);
}