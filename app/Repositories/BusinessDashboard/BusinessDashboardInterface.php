<?php
namespace App\Repositories\BusinessDashboard;
interface BusinessDashboardInterface{
    public function totalSales($request);
    public function totalPos($request);
    public function totalPurchase($request);
    public function TotalPurchaseReturn($request);

    public function ThirtyDaysSalesChart();

    public function recentSales($request);
    public function recentPos($request);

    public function summeryCount($request);
    public function salesChartsData();
    public function purchaseChartsData();
}