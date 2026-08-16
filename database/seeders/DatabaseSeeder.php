<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Account\Database\Seeders\AccountDatabaseSeeder;
use Modules\AccountHead\Database\Seeders\AccountHeadDatabaseSeeder;
use Modules\ApplyLeave\Database\Seeders\ApplyLeaveDatabaseSeeder;
use Modules\AssetCategory\Database\Seeders\AssetCategoryDatabaseSeeder;
use Modules\Assets\Database\Seeders\AssetsDatabaseSeeder;
use Modules\Attendance\Database\Seeders\AttendanceDatabaseSeeder;
use Modules\Branch\Database\Seeders\BranchDatabaseSeeder;
use Modules\Brand\Database\Seeders\BrandDatabaseSeeder;
use Modules\Business\Database\Seeders\BusinessDatabaseSeeder;
use Modules\BusinessSettings\Database\Seeders\BarcodeSettingsTableSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Customer\Database\Seeders\CustomerDatabaseSeeder;
use Modules\Department\Database\Seeders\DepartmentDatabaseSeeder;
use Modules\Designation\Database\Seeders\DesignationDatabaseSeeder;
use Modules\Holiday\Database\Seeders\HolidayDatabaseSeeder;
use Modules\LeaveAssign\Database\Seeders\LeaveAssignDatabaseSeeder;
use Modules\LeaveType\Database\Seeders\LeaveTypeDatabaseSeeder;
use Modules\Weekend\Database\Seeders\WeekendDatabaseSeeder;
use Modules\DutySchedule\Database\Seeders\DutyScheduleDatabaseSeeder;
use Modules\Expense\Database\Seeders\ExpenseDatabaseSeeder;
use Modules\FundTransfer\Database\Seeders\FundTransferDatabaseSeeder;
use Modules\Income\Database\Seeders\IncomeDatabaseSeeder;
use Modules\Plan\Database\Seeders\PlanDatabaseSeeder;
use Modules\Pos\Database\Seeders\PosDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Purchase\Database\Seeders\PurchaseDatabaseSeeder;
use Modules\SaleProposal\Database\Seeders\SaleProposalDatabaseSeeder;
use Modules\Sell\Database\Seeders\SellDatabaseSeeder;
use Modules\Service\Database\Seeders\ServiceDatabaseSeeder;
use Modules\ServiceSale\Database\Seeders\ServiceSaleDatabaseSeeder;
use Modules\StockTransfer\Database\Seeders\StockTransferDatabaseSeeder;
use Modules\Subscription\Database\Seeders\SubscriptionDatabaseSeeder;
use Modules\Supplier\Database\Seeders\SupplierDatabaseSeeder;
use Modules\TaxRate\Database\Seeders\TaxRateDatabaseSeeder;
use Modules\Unit\Database\Seeders\UnitDatabaseSeeder;
use Modules\Variation\Database\Seeders\VariationDatabaseSeeder;
use Modules\Warranties\Database\Seeders\WarrantiesDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(database_path('dummydata/uploads.sql'))); 
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FlagIconSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(LanguageConfigSeeder::class);
        //settings
        $this->call(SettingsSeeder::class);
        $this->call(CurrencyDatabaseSeeder::class);
        $this->call(BusinessDatabaseSeeder::class);
        $this->call(BranchDatabaseSeeder::class);

        // $this->call(ProjectSeeder::class);
        // $this->call(TodoSeeder::class);
        // //hrm
        // $this->call(LeaveTypeDatabaseSeeder::class);
        // $this->call(DesignationDatabaseSeeder::class);
        // $this->call(DepartmentDatabaseSeeder::class);
        // $this->call(LeaveAssignDatabaseSeeder::class);
        // $this->call(ApplyLeaveDatabaseSeeder::class);
        // $this->call(WeekendDatabaseSeeder::class);
        // $this->call(HolidayDatabaseSeeder::class);
        // $this->call(DutyScheduleDatabaseSeeder::class);
        // $this->call(AttendanceDatabaseSeeder::class);
        // //plan
        // $this->call(PlanDatabaseSeeder::class);
        // $this->call(TaxRateDatabaseSeeder::class);
        // $this->call(SubscriptionDatabaseSeeder::class);
        // $this->call(CustomerDatabaseSeeder::class);
        // $this->call(SupplierDatabaseSeeder::class);
        // $this->call(ServiceDatabaseSeeder::class);
        // //product module seeder  
        // $this->call(CategoryDatabaseSeeder::class);
        // $this->call(BrandDatabaseSeeder::class);
        // $this->call(WarrantiesDatabaseSeeder::class);
        // $this->call(VariationDatabaseSeeder::class);
        // $this->call(UnitDatabaseSeeder::class);
        // $this->call(ProductDatabaseSeeder::class);
        // $this->call(PurchaseDatabaseSeeder::class);
        // $this->call(PosDatabaseSeeder::class);
        // $this->call(SellDatabaseSeeder::class);
        // $this->call(ServiceSaleDatabaseSeeder::class);
        // $this->call(StockTransferDatabaseSeeder::class);

        // //accounts
        // $this->call(AccountDatabaseSeeder::class);
        // $this->call(FundTransferDatabaseSeeder::class);
        // $this->call(AccountHeadDatabaseSeeder::class);
        // $this->call(ExpenseDatabaseSeeder::class);
        $this->call(BarcodeSettingsTableSeeder::class);

        // //sale proposal
        // $this->call(SaleProposalDatabaseSeeder::class);
        // $this->call(AssetCategoryDatabaseSeeder::class);
        // $this->call(AssetsDatabaseSeeder::class);
        // $this->call(IncomeDatabaseSeeder::class);
 
    }
 
 

}
