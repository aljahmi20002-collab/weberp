<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\ActivityLogController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\BackupController;
use App\Http\Controllers\Backend\CrudGeneratorController;
use App\Http\Controllers\Backend\GeneralSettingsController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\LoginActivityController;
use App\Http\Controllers\Backend\LoginSettingController;
use App\Http\Controllers\Backend\MailSettingsController;
use App\Http\Controllers\Backend\Profile\ProfileController;
use App\Http\Controllers\Backend\Role\RoleController;
use App\Http\Controllers\Backend\TodoListController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\ReCaptchaSettingController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\BranchDashboardController;
use App\Http\Controllers\BusinessDashboardController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\LocalizationController; 
use Illuminate\Support\Facades\Auth; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['XSS','IsInstalled']],function(){
   
        Auth::routes();
        //demo user login
        Route::get('/demo/login',                           [LoginController::class,'demoLogin'])->name('demo.login');
        //end demo user login
        Route::get('localization/{lang}',                   [LocalizationController::class,'setLocale'])->name('localization');
        //social authentication
        Route::get('/login/{social}',                       [LoginController::class,'socialRedirect'])->name('social.login');
        Route::get('/google/login',                         [LoginController::class,'authGoogleLogin']);//google login , need url add in  your google app
        Route::get('/facebook/login',                       [LoginController::class,'authFacebookLogin']);// facebook login, need url add in your facebook app
        //end social authentication

        //auth
        Route::get('verify-now',                            [RegisterController::class,'VerifyNow'])->name('verify.now');
        Route::get('register-mail/resend',                  [RegisterController::class,'resendRegisterMail'])->name('register.resend.verify.mail');
        Route::post('/password-reset-link',                 [ForgotPasswordController::class,'passwordResetLink'])->name('password.reset.link');
        Route::get('/password-reset/{token}',               [ForgotPasswordController::class,'passwordReset'])->name('custom.password.reset');
        Route::post('/password-update',                     [ForgotPasswordController::class,'passwordUpdate'])->name('custom.password.update');
        
        Route::group(['middleware' => 'auth'],function(){
            Route::get('/',                        [DashboardController::class,'index'])->name('dashboard.index');
            Route::get('/cache-clear',             [AdminController::class,'cacheClear'])->name('cache.clear');

            //admin dashboard

            //business dashboard
            Route::controller(BusinessDashboardController::class)->group(function(){
                Route::prefix('dashboard-sales')->name('dashboard.sales.')->group(function(){
                    Route::get('total',                  'TotalSales')->name('total');
                    Route::get('pos/total',              'TotalPos')->name('pos.total');
                    Route::get('purchase/total',         'TotalPurchase')->name('purchase.total');
                    Route::get('purchase-return/total',  'TotalPurchaseReturn')->name('purchase.return.total');
                    Route::get('recent-sales',            'RecentSales')->name('recent.sales');
                    Route::get('recent-pos',              'RecentPos')->name('recent.pos');
                });
            });

            //branch dashboard
            Route::controller(BranchDashboardController::class)->group(function(){
                Route::prefix('branch-dashboard-sales')->name('branch.dashboard.sales.')->group(function(){
                    Route::get('total',                  'TotalSales')->name('total');
                    Route::get('pos/total',              'TotalPos')->name('pos.total');
                    Route::get('purchase/total',         'TotalPurchase')->name('purchase.total');
                    Route::get('purchase-return/total',  'TotalPurchaseReturn')->name('purchase.return.total');
                    Route::get('recent-sales',            'RecentSales')->name('recent.sales');
                    Route::get('recent-pos',              'RecentPos')->name('recent.pos');
                });
            });

            //profile
            Route::group(['prefix'=>'profile'],function(){
                Route::get('/',                             [ProfileController::class,  'profile'])->name('profile.index');
                Route::post('/update',                      [ProfileController::class,  'ProfileUpdate'])->name('profile.update');
                Route::post('/update-account',              [ProfileController::class,  'ProfileUpdateAccount'])->name('profile.account.update');
                Route::post('/update-avatar',               [ProfileController::class,  'profileUpdateAvatar'])->name('profile.update.avatar');
                route::post('/update/password',             [ProfileController::class,  'UpdatePassword'])->name('update.password');
            });
            //end profile
            //role
            Route::group(['prefix' =>'role'],function(){
                Route::get('/',                             [RoleController::class,    'index'])->name('role.index')->middleware('hasPermission:role_read');
                Route::get('/get-roles',                    [RoleController::class,    'allRoles'])->name('role.all');
                Route::get('/create',                       [RoleController::class,    'create'])->name('role.create')->middleware('hasPermission:role_create');
                Route::post('/store',                       [RoleController::class,    'store'])->name('role.store')->middleware('hasPermission:role_create');
                Route::get('/edit/{id}',                    [RoleController::class,    'edit'])->name('role.edit')->middleware('hasPermission:role_update');
                Route::put('/update',                       [RoleController::class,    'update'])->name('role.update')->middleware('hasPermission:role_update');
                Route::delete('/delete/{id}',               [RoleController::class,    'delete'])->name('role.delete')->middleware('hasPermission:role_delete');
            });
            //end role
            //user
            Route::group(['prefix'=>'user'],function(){
                Route::get('/',                             [UserController::class,   'index'])->name('user.index')->middleware('hasPermission:user_read');
                Route::get('/create',                       [UserController::class,   'create'])->name('user.create')->middleware('hasPermission:user_create');
                Route::post('/store',                       [UserController::class,   'store'])->name('user.store')->middleware('hasPermission:user_create');
                Route::get('/edit/{id}',                    [UserController::class,   'edit'])->name('user.edit')->middleware('hasPermission:user_update');
                Route::put('/update',                       [UserController::class,   'update'])->name('user.update')->middleware('hasPermission:user_update');
                Route::delete('/delete/{id}',               [UserController::class,   'delete'])->name('user.delete')->middleware('hasPermission:user_delete');
                Route::get('/permissions/{id}',             [UserController::class,   'permissions'])->name('user.permissions')->middleware('hasPermission:user_permissions');
                Route::put('/permissions/update',           [UserController::class,   'permissionsUpdate'])->name('user.permissions.update')->middleware('hasPermission:user_permissions');
                Route::post('/status/change/{id}',          [UserController::class,   'StatusChange'])->name('user.status.change')->middleware('hasPermission:user_status_update');//ajax using
                Route::post('/ban/unban/{id}',              [UserController::class,   'BanUnban'])->name('user.ban.unban')->middleware('hasPermission:user_ban_unban');//ajax using
                Route::post('/business/branch/fetch',       [UserController::class,   'UserBusinessBranchFetch'])->name('user.business.branch.fetch')->middleware('hasPermission:user_create');//ajax using
                Route::get('view/{id}',                     [UserController::class,   'view'])->name('user.view');
                Route::get('attendance-total',              [UserController::class,   'attendanceTotal'])->name('user.attendance.total');
                Route::get('get-all',                       [UserController::class,    'getAll'])->name('user.get.all');
                Route::get('get-todolist/{id}',             [UserController::class,    'getUserTodoList'])->name('user.get.todolist');
            });
            //end user
            //todo list controller
            Route::prefix('todo')->name('todoList.')->group(function() {

                Route::get('/',                              [TodoListController::class,   'index'])->name('index')->middleware('hasPermission:todo_read');
                Route::get('/create',                        [TodoListController::class,   'create'])->name('create')->middleware('hasPermission:todo_create');
                Route::post('/store',                        [TodoListController::class,   'store'])->name('store')->middleware('hasPermission:todo_create');
                Route::get('/edit/{todoList}',               [TodoListController::class,   'edit'])->name('edit')->middleware('hasPermission:todo_update');
                Route::put('/update/{todoList}',             [TodoListController::class,   'update'])->name('update')->middleware('hasPermission:todo_update');
                Route::delete('/delete/{todoList}',          [TodoListController::class,   'destroy'])->name('delete')->middleware('hasPermission:todo_delete');
                Route::get('/status/update/{id}',            [TodoListController::class,   'statusUpdate'])->name('status.update')->middleware('hasPermission:todo_statusupdate');
                Route::get('business/users',                 [TodoListController::class,    'BusinessUsers'])->name('business.users');
                Route::get('view/{id}',                      [TodoListController::class,    'view'])->name('view');
                Route::get('get-all',                        [TodoListController::class,    'getAllTodoList'])->name('get.all');
            });
            //ProjectController
            Route::prefix('project')->name('project.')->group(function() {

                Route::get('/',                                 [ProjectController::class,   'index'])->name('index')->middleware('hasPermission:project_read');
                Route::get('/create',                           [ProjectController::class,   'create'])->name('create')->middleware('hasPermission:project_create');
                Route::post('/store',                           [ProjectController::class,   'store'])->name('store')->middleware('hasPermission:project_create');
                Route::get('/edit/{project}',                   [ProjectController::class,   'edit'])->name('edit')->middleware('hasPermission:project_update');
                Route::put('/update/{project}',                 [ProjectController::class,   'update'])->name('update')->middleware('hasPermission:project_update');
                Route::delete('/delete/{project}',              [ProjectController::class,   'destroy'])->name('delete')->middleware('hasPermission:project_delete');
                Route::get('business/branches',                 [ProjectController::class,   'businessBranches'])->name('business.branches')->middleware('hasPermission:project_create');
                Route::get('get-all',                           [ProjectController::class,    'getAllProject'])->name('get.all');
            });
            Route::prefix('language')->name('language.')->group(function(){
                Route::get('/',                              [LanguageController::class, 'index'])->name('index')->middleware('hasPermission:language_read');
                Route::get('/create',                        [LanguageController::class, 'create'])->name('create')->middleware('hasPermission:language_create');
                Route::post('/store',                        [LanguageController::class, 'store'])->name('store')->middleware('hasPermission:language_create');
                Route::get('/edit/{id}',                     [LanguageController::class, 'edit'])->name('edit')->middleware('hasPermission:language_update');
                Route::put('/update',                        [LanguageController::class, 'update'])->name('update')->middleware('hasPermission:language_update');
                Route::get('/edit/phrase/{id}',              [LanguageController::class, 'editPhrase'])->name('edit.phrase')->middleware('hasPermission:language_phrase');
                Route::post('/update/phrase/{code}',         [LanguageController::class, 'updatePhrase'])->name('update.phrase')->middleware('hasPermission:language_phrase');
                Route::delete('/delete/{id}',                [LanguageController::class, 'delete'])->name('delete')->middleware('hasPermission:language_delete');
                Route::get('get-all',                        [LanguageController::class, 'getAllLanguage'])->name('get.all')->middleware('hasPermission:language_read');
            });

            Route::prefix('settings')->name('settings.')->group(function(){
                //general settings
                Route::prefix('general-settings')->name('general.settings.')->group(function(){
                    Route::get('/',                          [GeneralSettingsController::class, 'index'])->name('index')->middleware('hasPermission:general_settings_read');
                    Route::put('/update',                    [GeneralSettingsController::class, 'generalSettingsUpdate'])->name('update')->middleware('hasPermission:general_settings_update');
                });
                //mail settings
                Route::prefix('mail-settings')->name('mail.settings.')->Group(function(){
                    Route::get('/',                          [MailSettingsController::class, 'index'])->name('index')->middleware('hasPermission:mail_settings_read');
                    Route::put('/update',                    [MailSettingsController::class, 'update'])->name('update')->middleware('hasPermission:mail_settings_update');
                    Route::post('/test-send-mail',           [MailSettingsController::class, 'testSendMail'])->name('testsendmail')->middleware('hasPermission:mail_settings_update');
                });
                //login settings
                Route::prefix('login-settings')->name('login.settings.')->group(function(){
                    Route::get('/',                          [LoginSettingController::class,  'index'])->name('index')->middleware('hasPermission:login_settings_read');
                    Route::post('update',                    [LoginSettingController::class,  'update'])->name('update')->middleware('hasPermission:login_settings_update');
                });
                Route::prefix('recaptcha-settings')->name('recaptcha.')->group(function(){
                    Route::get('/',                          [ReCaptchaSettingController::class, 'index'])->name('index')->middleware('hasPermission:recaptcha_settings_read');
                    Route::put('/update',                    [ReCaptchaSettingController::class, 'update'])->name('update')->middleware('hasPermission:recaptcha_settings_update');
                });
                //PAYMENT SETTINGS
                Route::prefix('payment-settings')->name('payment.settings.')->group(function(){
                    Route::get('/',                               [SettingsController::class, 'paymentSettingsIndex'])->name('index')->middleware('hasPermission:payment_settings_read');
                    Route::put('/update',                         [SettingsController::class, 'updateSettings'])->name('update')->middleware('hasPermission:payment_settings_update');
                });
            });

            //activity logs
            Route::prefix('activity-logs')->name('activity.logs.')->group(function(){
                Route::get('/',                               [ActivityLogController::class, 'index'])->name('index')->middleware('hasPermission:activity_logs_read');
                Route::get('/view/{id}',                      [ActivityLogController::class, 'view'])->name('view')->middleware('hasPermission:activity_logs_read');
                Route::get('get-all',                         [ActivityLogController::class, 'getAllActivityLogs'])->name('get.all');
            });
            //crud generator
            Route::prefix('crud-generator')->name('crud.generator.')->group(function(){
                Route::get('/',                               [CrudGeneratorController::class, 'index'])->name('index')->middleware('hasPermission:crud_generator_read');
                Route::post('store',                          [CrudGeneratorController::class, 'store'])->name('store')->middleware('hasPermission:crud_generator_create');
            });
            //login activity logs
            Route::prefix('login-activity')->name('login.activity.')->group(function(){
                Route::get('/',                               [LoginActivityController::class, 'index'])->name('index')->middleware('hasPermission:login_activity_read');
                Route::get('get-all',                         [LoginActivityController::class, 'getAllLoginActivity'])->name('get.all');
            });
            //backup
            Route::prefix('backup')->name('backup.')->group(function(){
                Route::get('/',                               [BackupController::class, 'index'])->name('index')->middleware('hasPermission:backup_read');
                Route::get('/download',                       [BackupController::class, 'BackupDownload'])->name('download')->middleware('hasPermission:backup_read');
            });
        });

});

