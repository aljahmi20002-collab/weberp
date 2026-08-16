<?php

namespace Database\Seeders;

use App\Models\Backend\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes  = [
                           'role' => [
                              'read'           => 'role_read',
                              'create'         => 'role_create',
                              'update'         => 'role_update',
                              'delete'         => 'role_delete',
                           ],
                           'users' => [
                              'read'           => 'user_read',
                              'create'         => 'user_create',
                              'update'         => 'user_update',
                              'delete'         => 'user_delete',
                              'permissions'    => 'user_permissions',
                              'ban_or_unban'   => 'user_ban_unban',
                              'status_update'  => 'user_status_update',
                           ],
                           'todo' => [
                              'read'           => 'todo_read',
                              'create'         => 'todo_create',
                              'update'         => 'todo_update',
                              'delete'         => 'todo_delete',
                              'status_update'  => 'todo_statusupdate',
                           ],
                           'project' => [
                              'read'           => 'project_read',
                              'create'         => 'project_create',
                              'update'         => 'project_update',
                              'delete'         => 'project_delete',
                              'status_update'  => 'project_statusupdate',
                           ],
                           'language'=>[
                              'read'           => 'language_read',
                              'create'         => 'language_create',
                              'update'         => 'language_update',
                              'phrase'         => 'language_phrase',
                              'delete'         => 'language_delete',
                           ],
                           'general_settings'=>[
                              'read'           => 'general_settings_read',
                              'update'         => 'general_settings_update',
                           ],
                           'mail_settings'=>[
                              'read'           => 'mail_settings_read',
                              'update'         => 'mail_settings_update',
                           ],
                           'login_settings'=>[
                              'read'       => 'login_settings_read',
                              'update'     => 'login_settings_update',
                           ],
                           'payment_settings'=>[
                              'read'       => 'payment_settings_read',
                              'update'     => 'payment_settings_update',
                           ],
                           'activity_logs'=>[
                              'read'       => 'activity_logs_read'
                           ],
                           'login_activity'=>[
                              'read'       => 'login_activity_read'
                           ],
                           'crud_generator'=>[
                              'read'           => 'crud_generator_read',
                              'create'         => 'crud_generator_create'
                           ],
                           'backup'=>[
                              'read'       => 'backup_read'
                           ],
                           'recaptcha'=>[
                              'read'       => 'recaptcha_settings_read',
                              'update'     => 'recaptcha_settings_update'
                           ],
                           //currency
                           'currency'=>[
                              'read'           => 'currency_read',
                              'create'         => 'currency_create',
                              'update'         => 'currency_update',
                              'delete'         => 'currency_delete',
                              'status_update'  => 'currency_status_update',
                           ],
                           //business
                           'business'=>[
                              'read'           => 'business_read',
                              'create'         => 'business_create',
                              'update'         => 'business_update',
                              'delete'         => 'business_delete',
                              'status_update'  => 'business_status_update',
                              //business branch manage
                              'branch_read'           => 'business_branch_read',
                              'branch_create'         => 'business_branch_create',
                              'branch_update'         => 'business_branch_update',
                              'branch_delete'         => 'business_branch_delete',
                              'branch_status_update'  => 'business_branch_status_update',
                           ],
                           //branch
                           'branch'=>[
                              'read'           => 'branch_read',
                              'create'         => 'branch_create',
                              'update'         => 'branch_update',
                              'delete'         => 'branch_delete',
                              'status_update'  => 'branch_status_update',
                           ],

                           //leave type
                           'leave_type'=>[
                              'read'           => 'leave_type_read',
                              'create'         => 'leave_type_create',
                              'update'         => 'leave_type_update',
                              'delete'         => 'leave_type_delete',
                              'status_update'  => 'leave_type_status_update',
                           ],
                           //designation
                           'designation'=>[
                              'read'           => 'designation_read',
                              'create'         => 'designation_create',
                              'update'         => 'designation_update',
                              'delete'         => 'designation_delete',
                              'status_update'  => 'designation_status_update',
                           ],
                           //Department
                           'department'=>[
                              'read'           => 'department_read',
                              'create'         => 'department_create',
                              'update'         => 'department_update',
                              'delete'         => 'department_delete',
                              'status_update'  => 'department_status_update',
                           ],
                           //Leave Assign
                           'leave_assign'=>[
                              'read'           => 'leave_assign_read',
                              'create'         => 'leave_assign_create',
                              'update'         => 'leave_assign_update',
                              'delete'         => 'leave_assign_delete',
                              'status_update'  => 'leave_assign_status_update',
                           ],
                           //apply leave
                           'apply_leave'=>[
                              'read'           => 'apply_leave_read',
                              'create'         => 'apply_leave_create',
                              'delete'         => 'apply_leave_delete',
                           ],
                           //apply leave
                           'available_leave'=>[
                              'read'           => 'available_leave_read',
                           ],
                           //leave request
                           'leave_request'=>[
                              'read'           => 'leave_request_read',
                              'approval'       => 'leave_request_approval',
                           ],
                           //attendance

                           //weekend
                           //Leave Assign
                           'weekend'=>[
                              'read'           => 'weekend_read',
                              'update'         => 'weekend_update',
                              'status_update'  => 'weekend_status_update',
                           ],
                           //holiday
                           'holiday'=>[
                              'read'           => 'holiday_read',
                              'create'         => 'holiday_create',
                              'update'         => 'holiday_update',
                              'delete'         => 'holiday_delete',
                              'status_update'  => 'holiday_status_update',
                           ],
                           //duty schedule
                           'duty_schedule'=>[
                              'read'           => 'duty_schedule_read',
                              'create'         => 'duty_schedule_create',
                              'update'         => 'duty_schedule_update',
                              'delete'         => 'duty_schedule_delete', 
                           ],
                           //attendance
                           'attendance'=>[
                              'read'           => 'attendance_read',
                              'create'         => 'attendance_create',
                              'update'         => 'attendance_update',
                              'delete'         => 'attendance_delete', 
                              'checkout'       => 'attendance_checkout',  
                           ],
                           
                           'reports'=>[
                              'attendance_reports'           => 'attendance_reports',  
                              'profit_loss_reports'          => 'profit_loss_reports',   
                              'product_wise_profit_reports'  => 'product_wise_profit_reports',
                              'product_wise_pos_profit_reports'  => 'product_wise_pos_profit_reports',
                              'expense_report'               => 'expense_report',
                              'stock_report'                 => 'stock_report',
                              "customer_sale_report"         => "customer_sale_report",
                              "customer_pos_report"          => "customer_pos_report",
                              "purchase_report"              => "purchase_report",
                              "purchase_return_report"       => "purchase_return_report",
                              "sale_report"                  => "sale_report",
                              "service_sale_report"          => "service_sale_report",
                              "pos_report"                   => "pos_report",
                              "supplier_report"              => "supplier_report"
                           ], 
                           
                           //plans
                           'plans'=>[
                              'read'           => 'plans_read',
                              'create'         => 'plans_create',
                              'update'         => 'plans_update',
                              'delete'         => 'plans_delete', 
                              'status_update'  => 'plans_status_update',
                           ],
                           //subscription
                           'subscriptions'=>[
                              'read'           => 'subscription_read', 
                              'change'         => 'subscription_change', 
                           ],
                           //Tax Rate
                           'tax_rate'=>[
                              'read'           => 'tax_rate_read',
                              'create'         => 'tax_rate_create',
                              'update'         => 'tax_rate_update',
                              'delete'         => 'tax_rate_delete', 
                              'status_update'  => 'tax_rate_status_update',
                           ],
                           //customer
                           'customer'=>[
                              'read'           => 'customer_read',
                              'create'         => 'customer_create',
                              'update'         => 'customer_update',
                              'delete'         => 'customer_delete', 
                              'status_update'  => 'customer_status_update',
                           ],
                           //customer
                           'suppliers'=>[
                              'read'           => 'supplier_read',
                              'create'         => 'supplier_create',
                              'update'         => 'supplier_update',
                              'delete'         => 'supplier_delete', 
                              'status_update'  => 'supplier_status_update',
                           ],
                           //services
                           'services'=>[
                              'read'           => 'service_read',
                              'create'         => 'service_create',
                              'update'         => 'service_update',
                              'delete'         => 'service_delete', 
                              'status_update'  => 'service_status_update',
                           ],

                           //products
                           'products'=>[
                              'read'           => 'product_read',
                              'create'         => 'product_create',
                              'update'         => 'product_update',
                              'delete'         => 'product_delete'
                           ],
                           //Purchase
                           'purchase'=>[
                              'read'           => 'purchase_read',
                              'create'         => 'purchase_create',
                              'update'         => 'purchase_update',
                              'delete'         => 'purchase_delete',
                              'status_update'  => 'purchase_status_update',
                              'read_payment'   => 'purchase_read_payment',
                              'add_payment'    => 'purchase_add_payment',
                              'update_payment' => 'purchase_update_payment', 
                              'delete_payment' => 'purchase_delete_payment' 
                           ],
                           //Purchase Return
                           'purchase_return'=>[
                              'read'           => 'purchase_return_read',
                              'create'         => 'purchase_return_create',
                              'update'         => 'purchase_return_update',
                              'delete'         => 'purchase_return_delete',
                              'read_payment'   => 'purchase_return_read_payment',
                              'add_payment'    => 'purchase_return_add_payment',
                              'update_payment' => 'purchase_return_update_payment', 
                              'delete_payment' => 'purchase_return_delete_payment' 
                           ],
                           //Sale 
                           'sale'=>[
                              'read'           => 'sale_read',
                              'create'         => 'sale_create',
                              'update'         => 'sale_update',
                              'delete'         => 'sale_delete',
                              'read_payment'   => 'sale_read_payment',
                              'add_payment'    => 'sale_add_payment',
                              'update_payment' => 'sale_update_payment', 
                              'delete_payment' => 'sale_delete_payment' 
                           ],

                           //service sale
                           'service_sale'=>[
                              'read'           => 'service_sale_read',
                              'create'         => 'service_sale_create',
                              'update'         => 'service_sale_update',
                              'delete'         => 'service_sale_delete',
                              'read_payment'   => 'service_sale_read_payment',
                              'add_payment'    => 'service_sale_add_payment',
                              'update_payment' => 'service_sale_update_payment', 
                              'delete_payment' => 'service_sale_delete_payment' 
                           ],

                           //Sale proposal 
                           'sale_proposal'=>[
                              'read'           => 'sale_proposal_read',
                              'create'         => 'sale_proposal_create',
                              'update'         => 'sale_proposal_update',
                              'delete'         => 'sale_proposal_delete',
                              'read_payment'   => 'sale_proposal_read_payment',
                              'add_payment'    => 'sale_proposal_add_payment',
                              'update_payment' => 'sale_proposal_update_payment', 
                              'delete_payment' => 'sale_proposal_delete_payment' 
                           ],
                           //invoice list
                           'invoice'=>[
                              'read'           => 'invoice_read', 
                              'view'           => 'invoice_view', 
                           ],

                           //Pos list 
                           'pos'=>[
                              'read'           => 'pos_read',
                              'create'         => 'pos_create',
                              'update'         => 'pos_update',
                              'delete'         => 'pos_delete', 
                              'read_payment'   => 'pos_read_payment',
                              'add_payment'    => 'pos_add_payment',
                              'update_payment' => 'pos_update_payment', 
                              'delete_payment' => 'pos_delete_payment' 
                           ],
                           //Stock Transfer
                           'stock_transfer'=>[
                              'read'           => 'stock_transfer_read',
                              'create'         => 'stock_transfer_create',
                              'update'         => 'stock_transfer_update',
                              'delete'         => 'stock_transfer_delete', 
                              'status_update'  => 'stock_transfer_status_update', 
                           ],

                           //Account Head
                           'account_head'=>[
                              'read'           => 'account_head_read',
                              'create'         => 'account_head_create',
                              'update'         => 'account_head_update',
                              'delete'         => 'account_head_delete',  
                              'status_update'  => 'account_head_status_update',  
                           ],

                           //Account
                           'account'=>[
                              'read'           => 'account_read',
                              'create'         => 'account_create',
                              'update'         => 'account_update',
                              'delete'         => 'account_delete',  
                              'status_update'  => 'account_status_update',  
                           ],

                           //bank Transaction
                           'bank_transaction'=>[
                              'read'           => 'bank_transaction_read', 
                           ],

                           //Fund Transfer
                           'fund_transfer'=>[
                              'read'           => 'fund_transfer_read',
                              'create'         => 'fund_transfer_create',
                              'update'         => 'fund_transfer_update',
                              'delete'         => 'fund_transfer_delete',   
                           ],

                           //Income
                           'income'=>[
                              'read'           => 'income_read',
                              'create'         => 'income_create',
                              'update'         => 'income_update',
                              'delete'         => 'income_delete',  
                           ],

                           //Expense
                           'expense'=>[
                              'read'           => 'expense_read',
                              'create'         => 'expense_create',
                              'update'         => 'expense_update',
                              'delete'         => 'expense_delete',  
                           ],


                           //categories 
                           'categories'=>[ 
                              'read'           => 'category_read',
                              'create'         => 'category_create',
                              'update'         => 'category_update',
                              'delete'         => 'category_delete', 
                              'status_update'  => 'category_status_update', 
                           ],
                           //brands
                           'brands'=>[   
                              'read'           => 'brand_read',
                              'create'         => 'brand_create',
                              'update'         => 'brand_update',
                              'delete'         => 'brand_delete', 
                              'status_update'  => 'brand_status_update',
                           ],
                           //warranties
                           'warranties'=>[   
                              'read'           => 'warranty_read',
                              'create'         => 'warranty_create',
                              'update'         => 'warranty_update',
                              'delete'         => 'warranty_delete', 
                              'status_update'  => 'warranty_status_update',
                           ],
                           //variations
                           'variations'=>[   
                              'read'           => 'variation_read',
                              'create'         => 'variation_create',
                              'update'         => 'variation_update',
                              'delete'         => 'variation_delete', 
                              'status_update'  => 'variation_status_update',
                           ],
                           //units
                           'units'=>[   
                              'read'           => 'unit_read',
                              'create'         => 'unit_create',
                              'update'         => 'unit_update',
                              'delete'         => 'unit_delete', 
                              'status_update'  => 'unit_status_update',
                           ],

                           'support'=>[
                                 'read'         =>'support_read',
                                 'create'       =>'support_create',
                                 'update'       =>'support_update',
                                 'delete'       =>'support_delete',
                                 'status_update'=>'support_status_update'
                           ],
                           'admin_support'=>[
                                 'read'  =>'supports_read',
                                 'create'=>'supports_create',
                                 'update'=>'supports_update',
                                 'delete'=>'supports_delete',
                                 'status_update'=> 'supports_status_update'
                           ],

                           'asset_category'=>[
                                 'read'  =>'asset_category_read',
                                 'create'=>'asset_category_create',
                                 'update'=>'asset_category_update',
                                 'delete'=>'asset_category_delete',
                                 'status_update'  => 'asset_category_status_update',
                              ],
 
                           'assets'   =>[
                                          'read'  => 'assets_read',
                                          'create'=> 'assets_create',
                                          'update'=> 'assets_update',
                                          'delete'=> 'assets_delete'
                              ],
                           'bulk_import'   =>[
                                    'category'        => 'category_bulk_import',
                                    'brand'           => 'brand_bulk_import',
                                    'customer'        => 'customer_bulk_import',
                                    'supplier'        => 'supplier_bulk_import',
                                    'product'         => 'product_bulk_import',
                                    'service'         => 'service_bulk_import',
                                    'asset_category'  => 'asset_category_bulk_import'
                              ],
 
                           'settings'=>[
                              'read'   => 'settings_read'
                           ] 
                       ];

       foreach ($attributes as $key => $value) {
            $permissions                = new Permission();
            $permissions->attributes    = $key ;
            $permissions->keywords      = $value;
            $permissions->save();
       }
    }
}
