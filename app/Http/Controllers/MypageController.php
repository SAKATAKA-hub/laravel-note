<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateNoteFomeRequest;
use App\Models\Hashtag;
use App\Models\Note;

class MypageController extends Controller
{
    # ノート一覧ページの表示(list)
    public function list( $seach_keys=null )
    {
        // return Auth::user()->id;
        return view('mypage.list');
    }


    # ノートの新規作成ページの表示(create_note)
    public function create_note()
    {
        $hashtags = Hashtag::where('user_id',Auth::user()->id)->get();
        return view('mypage.create_note',compact('hashtags') );
    }


    # 新規作成ノートの保存(store_note)
    public function store_note(CreateNoteFomeRequest $request)
    {
        return "store";
        // $note = new Note([

        // ]);
        $note =1;
        return redirect()->route('edit_note',$note);
    }


    # ノート編集ページの表示(edit_note)
    public function edit_note(Note $note)
    {
        return 'edit_note'.$note;
        return view('mypage.edit_note');
    }



}
