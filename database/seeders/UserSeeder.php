<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $user                = new User();
        $user->name          = 'Admin';
        $user->email         = 'admin@wemaxdevs.com';
        $user->role_id       =  1;
        $user->phone         = '01820000000';
        $user->user_type     = UserType::SUPERADMIN;
        $user->permissions   = $this->superadminPermissions();
        $user->password      = Hash::make(123456);
        $user->email_verified=  1;
        $user->save();

        $user                = new User();
        $user->name          = 'Business';
        $user->email         = 'business@wemaxdevs.com';
        $user->role_id       =  2;
        $user->phone         = '01820000000';
        $user->user_type     = UserType::ADMIN;
        $user->permissions   = $this->adminPermissions();
        $user->password      = Hash::make(123456);
        $user->business_owner= 1;
        $user->email_verified= 1;
        $user->save();

        $user                = new User();
        $user->business_id   = 1;
        $user->branch_id     = 1;
        $user->name          = 'Branch';
        $user->email         = 'branch@wemaxdevs.com';
        $user->role_id       =  3;
        $user->phone         = '0182000000';
        $user->user_type     = UserType::USER;
        $user->permissions   = $this->employeePermissions();
        $user->password      = Hash::make(123456);
        $user->email_verified=  1;
        $user->save();


        $user                = new User();
        $user->name          = 'employee';
        $user->email         = 'employee@wemaxdevs.com';
        $user->role_id       =  1;
        $user->phone         = '01820000000';
        $user->user_type     = UserType::SUPERADMIN;
        $user->permissions   = $this->userPermissions();
        $user->password      = Hash::make(123456);
        $user->email_verified=  1;
        $user->save();


        $user                = new User();
        $user->name          = 'Elite Group';
        $user->email         = 'elitegroup@wemaxdevs.com';
        $user->role_id       =  2;
        $user->phone         = '01820000000';
        $user->user_type     = UserType::ADMIN;
        $user->permissions   = $this->adminPermissions();
        $user->password      = Hash::make(123456);
        $user->business_owner= 1;
        $user->email_verified= 1;
        $user->save();

        for ($i=0; $i <= 2; $i++) {
            $user                = new User();
            $user->business_id   = 1;
            $user->branch_id     = 1;
            $user->name          = $faker->name;
            $user->email         = $faker->email;
            $user->role_id       =  3;
            $user->phone         = $faker->phoneNumber;
            $user->user_type     = UserType::USER;
            $user->permissions   = $this->employeePermissions();
            $user->password      = Hash::make(123456);
            $user->email_verified=  1;
            $user->save();
        }


        for ($i=3; $i <= 5; $i++) {
            $user                = new User();
            $user->business_id   = 1;
            $user->branch_id     = 2;
            $user->name          = $faker->name;
            $user->email         = $faker->email;
            $user->role_id       =  3;
            $user->phone         = $faker->phoneNumber;
            $user->user_type     = UserType::USER;
            $user->permissions   = $this->employeePermissions();
            $user->password      = Hash::make(123456);
            $user->email_verified=  1;
            $user->save();
        }


    }


    //admin permissions
    public function superadminPermissions(){
        return [
                'role_read',
                'role_create',
                'role_update',
                'role_delete',
                'user_read',
                'user_create',
                'user_update',
                'user_delete',
                'user_permissions',
                'user_ban_unban',
                'user_status_update',
                'todo_read',
                'todo_create',
                'todo_update',
                'todo_delete',
                'todo_statusupdate',
                'project_read',
                'project_create',
                'project_update',
                'project_delete',
                'project_statusupdate',

                'language_read',
                'language_create',
                'language_update',
                'language_delete',
                'language_phrase',
                'general_settings_read',
                'general_settings_update',
                'mail_settings_read',
                'mail_settings_update',
                'login_settings_read',
                'login_settings_update',
                'activity_logs_read',
                'login_activity_read',
                'backup_read',
                'recaptcha_settings_read',
                'recaptcha_settings_update',
                'payment_settings_read',
                'payment_settings_update',
                "crud_generator_read",
                "crud_generator_create",
                //currency
                'currency_read',
                'currency_create',
                'currency_update',
                'currency_delete',
                'currency_status_update',
                //business
                'business_read',
                'business_create',
                'business_update',
                'business_delete',
                'business_status_update',
                //business branch manage
                'business_branch_read',
                'business_branch_create',
                'business_branch_update',
                'business_branch_delete',
                'business_branch_status_update',

                //leave type
                'leave_type_read',
                'leave_type_create',
                'leave_type_update',
                'leave_type_delete',
                'leave_type_status_update',
                //designation
                'designation_read',
                'designation_create',
                'designation_update',
                'designation_delete',
                'designation_status_update',
                //department
                'department_read',
                'department_create',
                'department_update',
                'department_delete',
                'department_status_update',
                //leave assign
                'leave_assign_read',
                'leave_assign_create',
                'leave_assign_update',
                'leave_assign_delete',
                'leave_assign_status_update',
                //apply leave
                'apply_leave_read',
                'apply_leave_create',
                'apply_leave_delete',
                //leave request
                'leave_request_read',
                'leave_request_approval',
                //weekend
                'weekend_read',
                'weekend_update',
                'weekend_status_update',
                //holiday
                'holiday_read',
                'holiday_create',
                'holiday_update',
                'holiday_delete',
                'holiday_status_update',
                //duty schedule
                'duty_schedule_read',
                'duty_schedule_create',
                'duty_schedule_update',
                'duty_schedule_delete',
                //attendance  
                'attendance_read', 
                'attendance_create',
                'attendance_update',
                'attendance_delete', 
                'attendance_checkout',   
                'attendance_reports',
                'profit_loss_reports',
                'product_wise_profit_reports',
                'product_wise_pos_profit_reports',
                'expense_report',
                'stock_report',
                'settings_read',
                'customer_sale_report',
                'customer_pos_report',
                'purchase_report',
                "purchase_return_report",
                "sale_report",
                "service_sale_report",
                "pos_report",
                "supplier_report",
                //plans
                'plans_read',
                'plans_create',
                'plans_update',
                'plans_delete', 
                'plans_status_update',
                'subscription_read', 
                'subscription_change',

                //Tax Rate
                'tax_rate_read',
                'tax_rate_create',
                'tax_rate_update',
                'tax_rate_delete', 
                'tax_rate_status_update',
                //customer
                'customer_read',
                'customer_create',
                'customer_update',
                'customer_delete', 
                'customer_status_update',
                //supplier
                'supplier_read',
                'supplier_create',
                'supplier_update',
                'supplier_delete', 
                'supplier_status_update',

                //service 
                'service_read',
                'service_create',
                'service_update',
                'service_delete', 
                'service_status_update',

                
                //product category and subcategory
                'category_read',
                'category_create',
                'category_update',
                'category_delete', 
                'category_status_update',
                
                //brands
                'brand_read',
                'brand_create',
                'brand_update',
                'brand_delete', 
                'brand_status_update',

                //warranty
                'warranty_read',
                'warranty_create',
                'warranty_update',
                'warranty_delete', 
                'warranty_status_update',

                //variation
                'variation_read',
                'variation_create',
                'variation_update',
                'variation_delete', 
                'variation_status_update',
                //units
                'unit_read',
                'unit_create',
                'unit_update',
                'unit_delete', 
                'unit_status_update',

                
                //products
                'product_read',
                'product_create',
                'product_update',
                'product_delete',

                //purchase
                'purchase_read',
                'purchase_create',
                'purchase_update',
                'purchase_delete',
                'purchase_status_update',
                'purchase_read_payment',
                'purchase_add_payment',
                'purchase_update_payment', 
                'purchase_delete_payment',

                //purchase return
                'purchase_return_read',
                'purchase_return_create',
                'purchase_return_update',
                'purchase_return_delete',
                'purchase_return_read_payment',
                'purchase_return_add_payment',
                'purchase_return_update_payment',
                'purchase_return_delete_payment',

                //stock transfer 
                'stock_transfer_read',
                'stock_transfer_create',
                'stock_transfer_update',
                'stock_transfer_delete', 
                'stock_transfer_status_update', 

                //account head
                'account_head_read',
                'account_head_create',
                'account_head_update',
                'account_head_delete',  
                'account_head_status_update',  

                //account
                'account_read',
                'account_create',
                'account_update',
                'account_delete',
                'account_status_update',  

                'bank_transaction_read',

                'fund_transfer_read',
                'fund_transfer_create',
                'fund_transfer_update',
                'fund_transfer_delete', 

                //income
                'income_read',
                'income_create',
                'income_update',
                'income_delete',  

                
                //expense
                'expense_read',
                'expense_create',
                'expense_update',
                'expense_delete', 
            
                //sale
                'sale_read',
                'sale_create',
                'sale_update',
                'sale_delete',
                'sale_read_payment',
                'sale_add_payment',
                'sale_update_payment', 
                'sale_delete_payment',
                'invoice_read', 
                'invoice_view', 

                'sale_proposal_read',
                'sale_proposal_create',
                'sale_proposal_update',
                'sale_proposal_delete',
                'sale_proposal_read_payment',
                'sale_proposal_add_payment',
                'sale_proposal_update_payment', 
                'sale_proposal_delete_payment',

                
                // service sale
                'service_sale_read',
                'service_sale_create',
                'service_sale_update',
                'service_sale_delete',
                'service_sale_read_payment',
                'service_sale_add_payment',
                'service_sale_update_payment', 
                'service_sale_delete_payment' ,

                //pos
                'pos_read',
                'pos_create',
                'pos_update',
                'pos_delete', 
                'pos_read_payment',
                'pos_add_payment',
                'pos_update_payment', 
                'pos_delete_payment',
                
                'support_read',
                'support_create',
                'support_update',
                'support_delete',
                'support_status_update',

                'supports_read',
                'supports_create',
                'supports_update',
                'supports_delete',
                'supports_status_update',

                'asset_category_read',
                'asset_category_create',
                'asset_category_update',
                'asset_category_delete',
                'asset_category_status_update',

                'assets_read',
                'assets_create',
                'assets_update',
                'assets_delete',

                'category_bulk_import',
                'brand_bulk_import',
                'customer_bulk_import',
                'supplier_bulk_import',
                'product_bulk_import',
                'service_bulk_import',
                'asset_category_bulk_import'
 
            ];
    }
    //user permissions
    public function adminPermissions(){
        return [
                // 'role_read',
                'user_read',
                'user_create',
                'user_update',
                'user_permissions',
                'user_delete',
                'todo_read',
                'todo_create',
                'todo_update',
                'todo_delete',
                'todo_statusupdate',
                'project_read',
                'project_create',
                'project_update',
                'project_delete',
                'project_statusupdate',
                //branch
                'branch_read',
                'branch_create',
                'branch_update',
                'branch_delete',
                'branch_status_update',
                //leave type
                'leave_type_read',
                'leave_type_create',
                'leave_type_update',
                'leave_type_delete',
                'leave_type_status_update',
                //designation
                'designation_read',
                'designation_create',
                'designation_update',
                'designation_delete',
                'designation_status_update',
                //department
                'department_read',
                'department_create',
                'department_update',
                'department_delete',
                'department_status_update',
                //leave assign
                'leave_assign_read',
                'leave_assign_create',
                'leave_assign_update',
                'leave_assign_delete',
                'leave_assign_status_update',
                //apply leave
                'apply_leave_read',
                'apply_leave_create',
                'apply_leave_delete',
                //leave request
                'leave_request_read',
                'leave_request_approval',
                'available_leave_read',
                //weekend
                'weekend_read',
                //holiday
                'holiday_read',
                'holiday_create',
                'holiday_update',
                'holiday_delete',
                'holiday_status_update',
                //duty schedule
                'duty_schedule_read',
                'duty_schedule_create',
                'duty_schedule_update',
                'duty_schedule_delete',
                //attendance
                'attendance_read',
                'attendance_create',
                'attendance_update',
                'attendance_delete', 
                'attendance_checkout',  
                'attendance_reports',
                'profit_loss_reports',
                'product_wise_profit_reports',
                'product_wise_pos_profit_reports',
                'expense_report',
                'stock_report',
                'customer_sale_report',
                'customer_pos_report',
                'purchase_report',
                "purchase_return_report",
                "sale_report",
                "service_sale_report",
                "pos_report",
                "supplier_report",
                'settings_read', 

                //Tax Rate
                'tax_rate_read',
                'tax_rate_create',
                'tax_rate_update',
                'tax_rate_delete', 
                'tax_rate_status_update',

                //customer
                'customer_read',
                'customer_create',
                'customer_update',
                'customer_delete', 
                'customer_status_update',

                //supplier
                'supplier_read',
                'supplier_create',
                'supplier_update',
                'supplier_delete', 
                'supplier_status_update',
                //service 
                'service_read',
                'service_create',
                'service_update',
                'service_delete', 
                'service_status_update',

                
                // service sale
                'service_sale_read',
                'service_sale_create',
                'service_sale_update',
                'service_sale_delete',
                'service_sale_read_payment',
                'service_sale_add_payment',
                'service_sale_update_payment', 
                'service_sale_delete_payment' ,
                
                //product category and subcategory
                'category_read',
                'category_create',
                'category_update',
                'category_delete', 
                'category_status_update',

                //brands
                'brand_read',
                'brand_create',
                'brand_update',
                'brand_delete', 
                'brand_status_update',

                //warranty
                'warranty_read',
                'warranty_create',
                'warranty_update',
                'warranty_delete', 
                'warranty_status_update',

                //variation
                'variation_read',
                'variation_create',
                'variation_update',
                'variation_delete', 
                'variation_status_update',
                
                //units
                'unit_read',
                'unit_create',
                'unit_update',
                'unit_delete', 
                'unit_status_update',

                //products
                'product_read',
                'product_create',
                'product_update',
                'product_delete',
                
                //purchase
                'purchase_read',
                'purchase_create',
                'purchase_update',
                'purchase_delete',
                'purchase_status_update',
                'purchase_read_payment',
                'purchase_add_payment',
                'purchase_update_payment', 
                'purchase_delete_payment',

                
                //purchase return
                'purchase_return_read',
                'purchase_return_create',
                'purchase_return_update',
                'purchase_return_delete',
                'purchase_return_read_payment',
                'purchase_return_add_payment',
                'purchase_return_update_payment',
                'purchase_return_delete_payment',

                //stock transfer 
                'stock_transfer_read',
                'stock_transfer_create',
                'stock_transfer_update',
                'stock_transfer_delete', 
                'stock_transfer_status_update', 
                
                //account head
                'account_head_read',
                'account_head_create',
                'account_head_update',
                'account_head_delete',  
                'account_head_status_update',  

                //account 
                'account_read',
                'account_create',
                'account_update',
                'account_delete',
                'account_status_update',  

                'bank_transaction_read',

                'fund_transfer_read',
                'fund_transfer_create',
                'fund_transfer_update',
                'fund_transfer_delete', 

                //income
                'income_read',
                'income_create',
                'income_update',
                'income_delete',  
                
                //expense
                'expense_read',
                'expense_create',
                'expense_update',
                'expense_delete', 

                //sale
                'sale_read',
                'sale_create',
                'sale_update',
                'sale_delete',
                'sale_read_payment',
                'sale_add_payment',
                'sale_update_payment', 
                'sale_delete_payment',
                'invoice_read', 
                'invoice_view',
                
                'sale_proposal_read',
                'sale_proposal_create',
                'sale_proposal_update',
                'sale_proposal_delete',
                'sale_proposal_read_payment',
                'sale_proposal_add_payment',
                'sale_proposal_update_payment', 
                'sale_proposal_delete_payment',

                //pos
                'pos_read',
                'pos_create',
                'pos_update',
                'pos_delete', 
                'pos_read_payment',
                'pos_add_payment',
                'pos_update_payment', 
                'pos_delete_payment', 
                            
                'support_read',
                'support_create',
                'support_update',
                'support_delete',
                'support_status_update',


                //admin support
                'supports_read',
                'supports_create',
                'supports_update',
                'supports_delete',

                'asset_category_read',
                'asset_category_create',
                'asset_category_update',
                'asset_category_delete',
                'asset_category_status_update',

                'assets_read',
                'assets_create',
                'assets_update',
                'assets_delete',


                'category_bulk_import',
                'brand_bulk_import',
                'customer_bulk_import',
                'supplier_bulk_import',
                'product_bulk_import',
                'service_bulk_import',
                'asset_category_bulk_import'
                
              ];
    }
 
    //user permissions
    public function employeePermissions(){
        return [ 
                'todo_read',
                'project_read', 
                //apply leave
                'apply_leave_read',
                'apply_leave_create',
                'apply_leave_delete',
                'available_leave_read',
                //weekend
                'weekend_read',
                //holiday
                'holiday_read',
                //attendance
                'attendance_read',
                'attendance_create',
                'attendance_update', 
                'attendance_checkout',
                'attendance_reports', 
                'profit_loss_reports',
                'product_wise_profit_reports',
                'product_wise_pos_profit_reports',
                'expense_report',
                'stock_report',
                'customer_sale_report',
                'customer_pos_report',
                'purchase_report',
                "purchase_return_report",
                "sale_report",
                "service_sale_report",
                "pos_report",
                "supplier_report",
                //account 
                'account_read',
                'account_create',
                'account_update',
                'account_delete',
                'account_status_update',  
                'bank_transaction_read',

                //sale
                'sale_read',
                'sale_create',
                'sale_update',
                'sale_delete',
                'sale_read_payment',
                'sale_add_payment',
                'sale_update_payment', 
                'sale_delete_payment',
                'invoice_read', 
                'invoice_view', 

                'sale_proposal_read',
                'sale_proposal_create',
                'sale_proposal_update',
                'sale_proposal_delete',
                'sale_proposal_read_payment',
                'sale_proposal_add_payment',
                'sale_proposal_update_payment', 
                'sale_proposal_delete_payment',
                
                //pos
                'pos_read',
                'pos_create',
                'pos_update',
                'pos_delete',
                'pos_read_payment',
                'pos_add_payment',
                'pos_update_payment', 
                'pos_delete_payment',

                 // service sale
                 'service_sale_read',
                 'service_sale_create',
                 'service_sale_update',
                 'service_sale_delete',
                 'service_sale_read_payment',
                 'service_sale_add_payment',
                 'service_sale_update_payment', 
                 'service_sale_delete_payment' ,
                                
                'support_read',
                'support_create',
                'support_update',
                'support_delete',
 
                'asset_category_read',
                'asset_category_create',
                'asset_category_update',
                'asset_category_delete',
                'asset_category_status_update',

                'assets_read',
                'assets_create',
                'assets_update',
                'assets_delete',


                'category_bulk_import',
                'brand_bulk_import',
                'customer_bulk_import',
                'supplier_bulk_import',
                'product_bulk_import',
                'service_bulk_import',
                'asset_category_bulk_import'

            ];
    }



    public function userPermissions(){
        return [
            'role_read',
                'role_create',
                'role_update',
                'role_delete',
                'user_read',
                'user_create',
                'user_update',
                'user_delete',
                'user_permissions',
                'user_ban_unban',
                'user_status_update',
            
                'language_read',
                'language_create',
                'language_update',
                'language_delete',
                'language_phrase',
                'general_settings_read',
                'general_settings_update',
                'mail_settings_read',
                'mail_settings_update',
                'login_settings_read',
                'login_settings_update',
                'activity_logs_read',
                'login_activity_read',
                'backup_read',
                'recaptcha_settings_read',
                'recaptcha_settings_update',
                'payment_settings_read',
                'payment_settings_update',
                
                //currency
                'currency_read',
                'currency_create',
                'currency_update',
                'currency_delete',
                'currency_status_update',
                //business
                'business_read',
                'business_create',
                'business_update',
                'business_delete',
                'business_status_update',
                //business branch manage
                'business_branch_read',
                'business_branch_create',
                'business_branch_update',
                'business_branch_delete',
                'business_branch_status_update',
 
                //plans
                'plans_read',
                'plans_create',
                'plans_update',
                'plans_delete', 
                'plans_status_update',
                'subscription_read', 
                'subscription_change',

          
                'support_read',
                'support_create',
                'support_update',
                'support_delete',

                'supports_read',
                'supports_create',
                'supports_update',
                'supports_delete',
 
 
        ];
    }

 

}
