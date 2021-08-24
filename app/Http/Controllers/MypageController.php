<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    # ノート一覧ページの表示(list)
    public function list( $seach_keys=null )
    {
        // return "MypageController 'list' [$seach_keys] ";
        return view('mypage.list');
    }
}
