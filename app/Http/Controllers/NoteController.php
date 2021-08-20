<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    # 一覧ページ表示
    public function index()
    {
        return view('notes.index');
    }


    # ノートの表示(show)
    # 印刷ページ(print)


    # 新規投稿ページ(create)
    public function create()
    {
        return view('notes.create');
    }


    # 新規投稿(store)
    public function store(Request $request)
    {
        // 新規投稿の作成
        $note = new Note();
        $note->user_id = $request->user_id;
        $note->title = $request->title;
        $note->hashtags = $request->hashtags;
        // $note->main_image = $request->main_image;
        $note->main_image = "no-image";
        $note->main_color = $request->main_color;

        $note->save();

        // 投稿編集ページへリダイレクト
        return redirect()->route('notes.edit',$note);
    }


    # 投稿編集ページ(edit)
    public function edit(Note $note)
    {
        return view('notes.edit',compact('note'));
    }


}
