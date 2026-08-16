<?php
namespace App\Repositories\BranchDashboard;
interface BranchDashboardInterface {
    public function totalSales($request);
    public function totalPos($request);
    public function totalPurchase($request);
    public function TotalPurchaseReturn($request); 
    public function ThirtyDaysSalesChart(); 
    public function recentSales($request);
    public function recentPos($request);
}