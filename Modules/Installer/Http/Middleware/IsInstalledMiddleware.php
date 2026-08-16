<?php

namespace Modules\Installer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class IsInstalledMiddleware
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
          } catch (\Exception $e) {
            return redirect('install');
          }
       
        if (Config::get('app.app_installed') == 'yes' && Schema::hasTable('settings') && Schema::hasTable('users')  ) {
            return $next($request);
        }

        return redirect('install');
    }
}
