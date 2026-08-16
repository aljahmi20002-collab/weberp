<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function cacheClear(){
        if(isSuperadmin()):
            Artisan::call('optimize:clear');
        else: 
            Artisan::call('cache:clear');
        endif;
        Toastr::success(__('cache_clear_successfully'),__('success'));
        return redirect()->back();
    }
}
