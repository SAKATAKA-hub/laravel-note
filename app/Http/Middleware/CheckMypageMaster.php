<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckMypageMaster
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
        # マイページ管理者ID
        $mypage_master = '';
        if( isset($request->mypage_master) ){ $mypage_master = $request->mypage_master->id; }
        if( isset($request->note) ){ $mypage_master = $request->note->user_id; }

        # ログインユーザーがマイページ管理者以外の時は、ログインページへリダイレクト
        if( !(Auth::check() && Auth::user()->id == $mypage_master) )
        {
            return redirect()->route('login_form');
        }

        return $next($request);
    }

}
