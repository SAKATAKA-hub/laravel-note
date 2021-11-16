<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckAppDministrator
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
        # アプリケーション管理者権限が無ければログインページへリダイレクト
        if( !(Auth::check() && Auth::user()->app_dministrator) )
        {
            return redirect()->route('login_form');
        }

        return $next($request);
    }
}
