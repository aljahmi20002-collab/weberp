<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class IsNotInstalledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            \DB::connection()->getPdo();
        } catch (\Throwable $th) {
            return response()->view('installer::index');
        }
    
        if (Config::get('app.app_installed') == 'yes' && Schema::hasTable('settings') && Schema::hasTable('users') ) {
            return redirect('/');
        }

        return $next($request);
    }
}
