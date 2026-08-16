<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\SaleController;
use App\Http\Controllers\Api\V1\TodoListcontroller;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::middleware(['CheckApiKey'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('countries',     'countries');
            Route::post('login',        'login');
            Route::post('register',     'register');
            Route::post('resend-otp',   'resendOTP');
            Route::post('verify-now',   'VerifyNow');

            Route::post('reset-password-otp',   'resetPasswordOtpSend');
            Route::post('reset-password',       'passwordUpdate');

            Route::group(['middleware' => ['auth:sanctum']], function () {
                Route::post('logout',     'logout');
                Route::get('refresh',     'refresh');
                Route::get('profile',     'profile');
                Route::get('permissions', 'permissions');

                Route::middleware('ApiIsSubscribed')->group(function(){ 
 
                    Route::prefix('dashboard')->controller(DashboardController::class)->group(function(){
                        Route::get('summery-count','summeryCount');
                        Route::get('sales-charts', 'salesCharts');
                        Route::get('purchase-charts', 'purchaseCharts');
                    });
                    
                    //employee means user
                    Route::prefix('employee')->controller(UserController::class)->group(function () {
                        Route::get('/',                'index')->middleware('hasPermission:user_read');
                        Route::get('/create',          'create')->middleware('hasPermission:user_create');
                        Route::post('/store',          'store')->middleware('hasPermission:user_create');
                        Route::put('/update',          'update')->middleware('hasPermission:user_update');
                        Route::delete('/delete/{id}',  'delete')->middleware('hasPermission:user_delete');
                        Route::get('/{id}/view',       'userView');
                        Route::get('/{id}/attendance', 'attendance');
                    });
                    //end employee means user
    
                    //project
                    Route::prefix('project')->controller(ProjectController::class)->group(function () {
    
                        Route::get('/',                                  'index')->middleware('hasPermission:project_read');
                        Route::get('/create',                            'create')->middleware('hasPermission:project_create');
                        Route::post('/store',                            'store')->middleware('hasPermission:project_create');
                        Route::get('/edit/{id}',                         'edit')->middleware('hasPermission:project_update');
                        Route::put('/update',                            'update')->middleware('hasPermission:project_update');
                        Route::delete('/delete/{id}',                    'destroy')->name('delete')->middleware('hasPermission:project_delete');
                    });
                    //end project
    
                    //todo list
                    Route::prefix('todo')->controller(TodoListcontroller::class)->group(function () {
                        Route::get('/',                                 'index')->middleware('hasPermission:todo_read');
                        Route::get('/create',                           'create')->middleware('hasPermission:todo_create');
                        Route::post('/store',                           'store')->middleware('hasPermission:todo_create');
                        Route::get('/edit/{todoList}',                  'edit')->middleware('hasPermission:todo_update');
                        Route::put('/update',                           'update')->middleware('hasPermission:todo_update');
                        Route::delete('/delete/{todoList}',             'destroy')->middleware('hasPermission:todo_delete');
                        Route::get('/details/{id}',                     'details')->middleware('hasPermission:todo_read');
                    });
                    //end todo list
    
                    //Start Sale
                    Route::resource('sales', SaleController::class);
                    Route::controller(SaleController::class)->group(function () {
                        Route::get('/sale/customers', 'customers')->middleware('hasPermission:customer_read');
                        Route::get('/branch_list/{business_id}', 'branchList')->middleware('hasPermission:sale_read');
                        Route::get('/branch_wise_products/{id}', 'branchWiseProducts')->middleware('hasPermission:sale_read');
                        Route::get('/tax_list', 'taxList')->middleware('hasPermission:tax_rate_read');
                        Route::get('/get_tax/{id}', 'getTax')->middleware('hasPermission:tax_rate_read');
                    });
                    //End Sale
    
                    //Start Report
                    Route::controller(ReportController::class)->group(function () {
                        Route::get('/employees', 'employees')->middleware('hasPermission:user_read');
                        Route::post('/attendance_reports', 'attendanceReport')->middleware('hasPermission:attendance_reports');
                        Route::post('/profit_loss_reports', 'profitLossReport')->middleware('hasPermission:profit_loss_reports');
                        Route::post('/product_sale_profit_reports', 'productSaleProfitReport')->middleware('hasPermission:product_wise_pos_profit_reports');
                        Route::post('/expense_reports', 'expenseReport')->middleware('hasPermission:expense_report');
                        Route::post('/customer_sale_reports', 'customerSaleReport')->middleware('hasPermission:customer_sale_report');
                        Route::post('/customer_pos_reports', 'customerPosReport')->middleware('hasPermission:customer_pos_report');
                        Route::post('/purchase_reports', 'purchaseReport')->middleware('hasPermission:purchase_report');
                        
                        Route::get('/stock-reports-page',   'stockReportPage');
                        Route::post('/stock_reports',       'stockReport')->middleware('hasPermission:stock_report');
                        Route::post('/sale-reports',        'SaleReports')->middleware('hasPermission:sale_report');
                        Route::post('/service-sale-reports', 'serviceSaleReports')->middleware('hasPermission:service_sale_report');
                    });
                    //End Report
                });
                
            });
        });
    });
});
