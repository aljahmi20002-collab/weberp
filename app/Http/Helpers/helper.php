<?php

use App\Enums\BanUser;
use App\Enums\Status;
use App\Enums\TodoStatus;
use App\Enums\UserType;
use App\Models\Backend\CrudGenerator;
use App\Models\Backend\Language;
use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Models\Backend\Setting;
use App\Models\Backend\TodoList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Modules\Account\Entities\Account;
use Modules\Account\Enums\AccountType;
use Modules\ApplyLeave\Entities\LeaveRequest;
use Modules\ApplyLeave\Enums\LeaveStatus;
use Modules\Attendance\Entities\Attendance;
use Modules\Attendance\Enums\AttendanceStatus;
use Modules\Business\Entities\Business;
use Modules\Currency\Entities\Currency;
use Modules\Holiday\Entities\Holiday;
use Modules\LeaveAssign\Entities\LeaveAssign;
use Modules\Plan\Entities\Plan;
use Modules\Plan\Enums\IsDefault;
use Modules\Product\Entities\ProductVariation;
use Modules\Purchase\Entities\PurchaseItem;
use Modules\Subscription\Entities\Subscription;

use function PHPSTORM_META\type;

// default language
if (!function_exists('defaultLanguage')) {
    function defaultLanguage()
    {
        $default_lang = settings('default_language');
        if ($default_lang) :
            return $default_lang;
        endif;
        return 'en';
    }
}
//end default language

// permission check
if (!function_exists('hasPermission')) {
    function hasPermission($permission = null)
    {
        if (in_array($permission, Auth::user()->permissions)) :
            return true;
        endif;
        return false;
    }
}
//end permission check


// date format
if (!function_exists('dateFormat')) {
    function dateFormat($date)
    {
        $date  = DateTime::createFromFormat('d/m/Y', $date);
        return Carbon::parse($date)->format('d M Y');
    }
}
//end date format


// date format2
if (!function_exists('dateFormat2')) {
    function dateFormat2($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }
}
//end date format2


// date time format
if (!function_exists('dateTimeFormat')) {
    function dateTimeFormat($date)
    {
        return Carbon::parse($date)->format('d M Y h:i:s A');
    }
}
//end date time format


// time format
if (!function_exists('timeFormat')) {
    function timeFormat($time)
    {
        return Carbon::parse($time)->format('h:i:s A');
    }
}
//end time format


//fetch all active language
if (!function_exists('language')) {
    function language()
    {
        return Language::where('status', Status::ACTIVE)->get();
    }
}

//fetch active language icon
if (!function_exists('languageicon')) {
    function languageicon($code)
    {
        $lang = Language::where('code', $code)->where('status', Status::ACTIVE)->first();
        return $lang->icon_class;
    }
}

if (!function_exists('oldLogDetails')) {
    function oldLogDetails($oldLogs, $newLogs)
    {
        foreach ($oldLogs as $key => $value) {
            if ($newLogs == $key) {
                return $value;
            }
        }
    }
}

if (!function_exists('demoUsers')) {
    function demoUsers()
    {
        return User::where(['status' => Status::ACTIVE, 'is_ban' => BanUser::UNBAN])->limit(4)->get();
    }
}

//rtl and ltr
if (!function_exists('languageDirection')) {

    function languageDirection($text_direction)
    {
        $lang = Language::where('code', $text_direction)->first();
        if ($lang) :
            return $lang->text_direction;
        endif;
        return 'ltr';
    }
}


if (!function_exists('todoChart')) {

    function todoChart($month, $status)
    {
        $date     = Carbon::createFromDate(Date('Y-') . $month . Date('-d'));
        $startDay = $date->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
        $endOfDay = $date->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');
        $todo     = TodoList::with('business', 'user', 'upload')->where('status', $status)->whereBetween('created_at', [$startDay, $endOfDay])->orderByDesc('id')->get();
        return $todo->count();
    }
}


if (!function_exists('static_asset')) {

    function static_asset($path = '')
    {
        if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')) {
            return app('url')->asset($path);
        } else {
            return app('url')->asset('public/' . $path);
        }
    }
}


if (!function_exists('crud_generator')) {
    function crud_generator()
    {
        return CrudGenerator::orderBy('id', 'asc')->get();
    }
}

if (!function_exists('statusUpdate')) {
    function statusUpdate($status)
    {
        $Text = '';
        if ($status == Status::ACTIVE) :
            $Text = __('inactive');
        elseif ($status == Status::INACTIVE) :
            $Text =  __('active');
        endif;
        return $Text;
    }
}

if (!function_exists('currency')) {
    function currency()
    {
        return Currency::where('status', Status::ACTIVE)->orderBy('position', 'asc')->get();
    }
}

if (!function_exists('business')) {
    function business()
    {
        if (Auth::user()->user_type ==  UserType::ADMIN) :
            return true;
        endif;
        return false;
    }
}
if (!function_exists('isSuperadmin')) {
    function isSuperadmin()
    {
        if (Auth::user()->user_type ==  UserType::SUPERADMIN) :
            return true;
        endif;
        return false;
    }
}
if (!function_exists('isUser')) {
    function isUser()
    {
        if (Auth::user()->user_type ==  UserType::USER) :
            return true;
        endif;
        return false;
    }
}

if (!function_exists('business_id')) {
    function business_id()
    {
        if (Auth::user()->business) :
            return Auth::user()->business->id;
        elseif (Auth::user()->userBusiness) :
            return Auth::user()->userBusiness->id;
        else :
            return null;
        endif;
    }
}

if (!function_exists('businessId')) {
    function businessId($user_id)
    {
        $user  = User::find($user_id);
        if ($user && $user->business) :
            return $user->business->id;
        elseif ($user && $user->userBusiness) :
            return $user->userBusiness->id;
        else :
            return null;
        endif;
    }
}


if (!function_exists('business_name')) {
    function business_name($user_id)
    {
        $user = User::find($user_id);
        if ($user->business && $user->user_type == UserType::ADMIN) :
            return $user->business->business_name;
        elseif ($user->userBusiness && $user->user_type == UserType::ADMIN) :
            return $user->userBusiness->business_name;
        elseif ($user->userBusiness && $user->user_type == UserType::USER) :
            return $user->userBusiness->business_name;
        else :
            return '';
        endif;
    }
}


if (!function_exists('purchaseDefaultAccount')) {
    function purchaseDefaultAccount()
    {
        $account =  Account::where(['business_id' => business_id(), 'account_type' => AccountType::ADMIN])->where('is_default', IsDefault::YES)->select('id')->first();
        return  $account->id ?? null;
    }
}
if (!function_exists('saleDefaultAccount')) {
    function saleDefaultAccount($branch_id = null)
    {
        $account =  Account::where('business_id', business_id())->where(function ($query) use ($branch_id) {
            if (business()) :
                $query->where(['account_type' => AccountType::BRANCH, 'branch_id' => $branch_id]);
            else :
                $query->where(['account_type' => AccountType::BRANCH, 'branch_id' => Auth::user()->branch_id]);
            endif;
        })->where('is_default', IsDefault::YES)->select('id')->first();
        return  $account->id ?? null;
    }
}

if (!function_exists('user_branch_name')) {
    function user_branch_name($user_id)
    {
        $user = User::find($user_id);
        if ($user->branch && $user->user_type == UserType::USER) :
            return $user->branch->name;
        else :
            return null;
        endif;
    }
}

if (!function_exists('businessLogo')) {
    function businessLogo()
    {
        if (
            Auth::user()->user_type ==  UserType::ADMIN &&
            Auth::user()->business &&
            Auth::user()->business->upload  &&
            File::exists(public_path(Auth::user()->business->upload->original))
        ) :
            return static_asset(Auth::user()->business->upload->original);
        elseif (
            Auth::user()->user_type ==  UserType::ADMIN &&
            Auth::user()->userBusiness &&
            Auth::user()->userBusiness->upload &&
            File::exists(public_path(Auth::user()->userBusiness->upload->original))
        ) :
            return static_asset(Auth::user()->userBusiness->upload->original);
        elseif (
            Auth::user()->user_type ==  UserType::USER &&
            Auth::user()->userBusiness &&
            Auth::user()->userBusiness->upload  &&
            File::exists(public_path(Auth::user()->userBusiness->upload->original))
        ) :
            return static_asset(Auth::user()->userBusiness->upload->original);
        else :
            return settings('logo');
        endif;
    }
}

if (!function_exists('user_type_text')) {
    function user_type_text($user_type = null)
    {
        switch ($user_type) {
            case (\App\Enums\UserType::SUPERADMIN):
                return  __('superadmin');
                break;
            case (\App\Enums\UserType::ADMIN):
                return  __('admin');
                break;
            case (\App\Enums\UserType::USER):
                return  __('user');
            default:
                return '';
                break;
        }
    }
}

if (!function_exists('MyLeave')) {
    function MyLeave($leave_assign_id, $user_id, $role_id)
    {
        $leaveAssign   = LeaveAssign::find($leave_assign_id);
        $leaveRequests = LeaveRequest::where([
            'employee_id'    => $user_id,
            'leave_assign_id' => $leave_assign_id,
            'role_id'        => $role_id,
            'status'         => LeaveStatus::APPROVED
        ])->whereYear('created_at', Date('Y'))->get();

        $approvedDays  = 0;
        foreach ($leaveRequests as  $leave) {
            $start_time    =  Carbon::parse($leave->leave_from)->startOfDay()->toDateTimeString();
            $end_time      =  Carbon::parse($leave->leave_to)->endOfDay()->addMinute(1)->toDateTimeString();
            $approvedDays +=  Carbon::parse($start_time)->diff($end_time)->days;
        }

        $remaining_days = ($leaveAssign->days - $approvedDays);
        return $remaining_days;
    }
}

if (!function_exists('dateDay')) {
    function dateDay($date)
    {
        $data       = [];
        $data['d']  = Carbon::parse($date)->format('d');
        $data['D']  = Carbon::parse($date)->format('D');
        return $data;
    }
}

if (!function_exists('totalPresent')) {
    function totalPresent($employee_id, $data)
    {
        $from  = $data['start_month'];
        $to    = Carbon::parse($data['end_month'])->subSecond(1)->toDateTimeString();

        $attendance = Attendance::where('employee_id', $employee_id)->where('status', AttendanceStatus::CHECK_OUT)->whereBetween('date', [$from, $to])->get();
        return $attendance->count();
    }
}

function holidayDates()
{
    //holiday
    $holidays      = Holiday::where('status', Status::ACTIVE)->whereYear('from', date('Y'))->whereMonth('from', date('m'))->get();
    $holiday_dates = [];
    foreach ($holidays as $holiday) {
        $days            = Carbon::parse($holiday->from)->diffInDays($holiday->to);
        for ($i = 0; $i <= $days; $i++) {
            $holiday_dates[] =  Carbon::parse($holiday->from)->addDays($i)->format('Y-m-d');
        }
    }
    return $holiday_dates;
}

function leaveDates($employee_id)
{
    $leave_requests      = LeaveRequest::where('employee_id', $employee_id)->where('status', LeaveStatus::APPROVED)->whereYear('leave_from', date('Y'))->whereMonth('leave_from', date('m'))->get();

    $leave_dates = [];
    foreach ($leave_requests as $key => $leave) {
        $leavedays            = Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to);
        for ($i = 0; $i <= $leavedays; $i++) {
            $leave_dates[] =  Carbon::parse($leave->leave_from)->addDays($i)->format('Y-m-d');
        }
    }
    return $leave_dates;
}

if (!function_exists('attendanceStatus')) {
    function attendanceStatus($date, $request)
    {

        $reqdate = explode('To', $request->date);
        $data = [];
        if (is_array($reqdate)) {
            $data['from']   = Carbon::parse(trim($reqdate[0]))->startOfDay()->toDateTimeString();
            $data['to']     = Carbon::parse(trim($reqdate[1]))->endOfDay()->toDateTimeString();
        }
        // //holiday check
        $holidays      = $request->getHolidays;
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $days            = Carbon::parse($holiday->from)->diffInDays($holiday->to);
            for ($i = 0; $i <= $days; $i++) {
                $holiday_dates[] =  Carbon::parse($holiday->from)->addDays($i)->format('Y-m-d');
            }
        }
        if (in_array($date, $holiday_dates)) :
            return '<span class="m-2" title="' . __('holiday') . '"><i class="fa fa-star text-warning "></i> ' . __('holiday') . '</span>';
        endif;
        //end holiday check
        //leave check
        $leave_requests  = LeaveRequest::where('employee_id', $request->employee_id)->where('status', LeaveStatus::APPROVED)->where(function ($query) use ($data) {
            $query->whereBetween('leave_from', [$data['from'], $data['to']]);
            $query->orWhereBetween('leave_to', [$data['from'], $data['to']]);
        })->get();


        $leave_dates = [];
        foreach ($leave_requests as $key => $leave) {
            $leavedays            = Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to);
            for ($i = 0; $i <= $leavedays; $i++) {
                $leave_dates[] =  Carbon::parse($leave->leave_from)->addDays($i)->format('Y-m-d');
            }
        }

        if (in_array($date, $leave_dates)) :

            return '<span class="m-2 text-danger leave-icon" title="' . __('on_leave') . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg> ' . __('on_leave') . '</span>';
        endif;
        //end leave check

        $absent          =  '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg> ' . __('absent');
        $present         = '<i class="fa fa-check text-success"></i> ' . __('present');
        $notCheckout     = '<i class="fa fa-hourglass-start text-primary"></i> ' . __('not_check_out');

        $attendance = Attendance::with('user')->where(['employee_id' => $request->employee_id])->whereDate('date', $date)->first();

        if ($attendance) :
            if ($attendance->status == AttendanceStatus::PENDING) :
                return $notCheckout;
            else :
                return $present;
            endif;
        else :
            return  $absent;
        endif;
    }
}

if (!function_exists('attendanceStatusText')) {
    function attendanceStatusText($date, $request)
    {
        $reqdate = explode('To', $request->date);
        $data = [];
        if (is_array($reqdate)) {
            $data['from']   = Carbon::parse(trim($reqdate[0]))->startOfDay()->toDateTimeString();
            $data['to']     = Carbon::parse(trim($reqdate[1]))->endOfDay()->toDateTimeString();
        }

        //holiday check
        $holidays      = $request->getHolidays;
        $holiday_dates = [];
        foreach ($holidays as $holiday) {
            $days            = Carbon::parse($holiday->from)->diffInDays($holiday->to);
            for ($i = 0; $i <= $days; $i++) {
                $holiday_dates[] =  Carbon::parse($holiday->from)->addDays($i)->format('Y-m-d');
            }
        }
        if (in_array($date, $holiday_dates)) :
            return __('holiday');
        endif;
        //end holiday check

        //leave check
        $leave_requests  = LeaveRequest::where('employee_id', $request->employee_id)->where('status', LeaveStatus::APPROVED)->where(function ($query) use ($data) {
            $query->whereBetween('leave_from', [$data['from'], $data['to']]);
            $query->orWhereBetween('leave_to', [$data['from'], $data['to']]);
        })->get();

        $leave_dates = [];
        foreach ($leave_requests as $key => $leave) {
            $leavedays            = Carbon::parse($leave->leave_from)->diffInDays($leave->leave_to);
            for ($i = 0; $i <= $leavedays; $i++) {
                $leave_dates[] =  Carbon::parse($leave->leave_from)->addDays($i)->format('Y-m-d');
            }
        }

        if (in_array($date, $leave_dates)) :
            return __('on_leave');
        endif;
        //end leave check

        $absent          =  __('absent');
        $present         = __('present');
        $notCheckout     = __('not_check_out');

        $attendance = Attendance::with('user')->where(['employee_id' => $request->employee_id])->whereDate('date', $date)->first();

        if ($attendance) :
            if ($attendance->status == AttendanceStatus::PENDING) :
                return $notCheckout;
            else :
                return $present;
            endif;
        else :
            return  $absent;
        endif;
    }
}


if (!function_exists('ReportAttendanceFind')) {
    function ReportAttendanceFind($employee_id, $date)
    {
        return Attendance::where('employee_id', $employee_id)->whereDate('date', $date)->first();
    }
}
if (!function_exists('dayAttendance')) {
    function dayAttendance($employee_id, $date)
    {

        //holiday check
        if (in_array($date, holidayDates())) :
            return '<span class="m-2" title="' . __('holiday') . '"><i class="fa fa-star text-warning "></i></span>';
        //end holiday check
        endif;
        //leave check
        if (in_array($date, leaveDates($employee_id))) :
            return '<span class="m-2 text-danger leave-icon" title="' . __('on_leave') . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
          </svg></span>';
        endif;
        //end leave check

        $absent    =  '<a href="#" class="modalBtn" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-title="' . __('mark_attendance') . '" data-url="' . route('hrm.attendance.create.modal', ['employee_id' => $employee_id, 'date' => $date]) . '"  data-bs-toggle="tooltip" title="' . __('absent') . '"
        data-bs-placement="top" > <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg></a>';

        $present     = '<a href="#" class="modalBtn" data-bs-toggle="modal" data-bs-target="#dynamic-modal" data-title="' . __('attendance_details') . '" data-url="' . route('hrm.attendance.details.modal', ['employee_id' => $employee_id, 'date' => $date]) . '" data-bs-toggle="tooltip" title="' . __('present') . '"
        data-bs-placement="top"><i class="fa fa-check text-success"></i></a>';

        $notCheckout     = '<a href="#" class="modalBtn" data-bs-toggle="modal" data-bs-target="#dynamic-modal"  data-title="' . __('check_out_attendance') . '" data-url="' . route('hrm.attendance.checkout.modal', ['employee_id' => $employee_id, 'date' => $date]) . '" data-bs-toggle="tooltip" title="' . __('present') . '"
        data-bs-placement="top"><i class="fa fa-hourglass-start text-primary"></i></a>';
        $attendance = Attendance::where(['employee_id' => $employee_id])->whereDate('date', $date)->first();


        if ($attendance) :
            if ($attendance->status == AttendanceStatus::PENDING) :
                return $notCheckout;
            else :
                return $present;
            endif;
        else :
            return $absent;
        endif;
    }
}



if (!function_exists('systemCurrency')) {
    function systemCurrency()
    {
        return settings('currency');
    }
}

if (!function_exists('businessCurrency')) {
    function businessCurrency($business_id)
    {

        if (!Session::get('businessCurrency')) :
            $bussiness  = Business::find($business_id);
            if ($bussiness) :
                $currency = Session::put('businessCurrency', @$bussiness->currency->symbol);
                return Session::get('businessCurrency');
            endif;
            return Session::put('businessCurrency', '');
        endif;
        return Session::get('businessCurrency');
    }
}

if (!function_exists('businessPlanPermission')) {
    function businessPlanPermission($plan_id)
    {
        $plan  = Plan::find($plan_id);
        $role  = Role::find(2);
        $permissionsModules = Permission::whereIn('attributes', $plan->modules)->get();
        $permissions = [];
        foreach ($permissionsModules as  $permissionModule) {
            foreach ($permissionModule->keywords as  $keyword) {
                if (in_array($keyword, $role->permissions)) :
                    $permissions[] = $keyword;
                endif;
            }
        }
        return $permissions;
    }
}


if (!function_exists('isSubscribed')) {
    function isSubscribed()
    {
        if (!isSuperadmin()) :
            $subscription = Subscription::where('business_id', business_id())->first();
            if ($subscription) :
                $todayDate       = Carbon::today()->startOfDay()->toDateTimeString();
                $end_date        = Carbon::parse($subscription->end_date)->endOfDay()->addSecond(1)->toDateTimeString();
            
                $today_strtotime = strtotime($todayDate);
                $end_strtotime   = strtotime($end_date);
                if ($today_strtotime <= $end_strtotime) :
                    return true; //subscribed
                else :
                    return false; //expired
                endif;
            else :
                return false; //not subscribed
            endif;
        endif;
        return true; //is super admin
    }
}


if (!function_exists('productVariation')) {
    function productVariation($id)
    {
        return ProductVariation::find($id);
    }
}

if (!function_exists('productTotalSalePrice')) {
    function productTotalSalePrice($product)
    {
        $saleAmount = 0;
        foreach ($product as $variation_location) {
            $saleAmount += $variation_location->SaleTotalUnitPrice;
        }
        return $saleAmount;
    }
}

if (!function_exists('productTotalPurchaseCost')) {
    function productTotalPurchaseCost($product, $date)
    {
        $date = explode(' - ', $date);
        if (is_array($date)) {
            $from   = Carbon::parse(trim($date[0]))->startOfDay()->toDateTimeString();
            $to     = Carbon::parse(trim($date[1]))->endOfDay()->toDateTimeString();
        }
        $totalPurchaseCost = 0;
        foreach ($product as $variation) {
            $totalPurchaseCost  += PurchaseItem::where('vari_loc_det_id', $variation->id)->whereBetween('updated_at', [$from, $to])->sum('total_unit_cost');
        }
        return $totalPurchaseCost;
    }
}

if (!function_exists('productTotalPosPrice')) {
    function productTotalPosPrice($product)
    {
        $posAmount = 0;
        foreach ($product as $variation_location) {
            $posAmount += $variation_location->PosTotalUnitPrice;
        }
        return $posAmount;
    }
}
 
