<?php
namespace Modules\Reports\Repositories\ProfitLossReport;
interface ProfitLossReportInterface {
    public function getSaleData($request); 
    public function getPosData($request);
    public function getPurchaseData($request);
    public function getPurchaseReturnData($request);
    public function getStocktransferData($request);
    public function getIncomeData($request);
    public function getExpenseData($request);
}