<?php

namespace App\Http\Middleware;

use App\Models\Backend\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class LocalizationManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if(Session::has('locale')){//if language change
            if(env('APP_INSTALLED') == 'yes' && Schema::hasTable('settings')):
                $lang = Language::where('code',Session::get('locale'))->first();
            else:
                $lang = false;
            endif;
            if(!$lang ):
                App::setLocale('en');
                Session::put('locale','en');
            endif;
            if(File::exists(base_path('/lang/'.Session::get('locale')))):
                App::setlocale(Session::get('locale'));
            endif;
        }else{//default language
            if(env('APP_INSTALLED') == 'yes' && Schema::hasTable('settings')):
                $lang = Language::where('code',defaultLanguage())->first();
            else:
                $lang = false;
            endif;
            if($lang && File::exists(base_path('/lang/'.$lang->code)))://if was find language and language folder
                App::setlocale(defaultLanguage());
            else:
                Session::put('locale','en');
                App::setlocale(Session::get('locale'));
            endif;
        }

        return $next($request);
    }
}
