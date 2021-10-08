<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Note;
use App\Models\Textbox;


class EditTextboxController extends Controller
{
    /**
     * テキストボックス新規作成ページの表示(create_textbox)
     *
     *
     * @param \App\Models\Note $note
     * @param Int $order
     * @return \Illuminate\View\View
     */
    public function create_textbox(Note $note, $order){

        return view('edit.textbox',compact('note','order'));
    }




    /**
     * テキストボックス新規作成ページの保存(store_textbox)
     *
     *
     * @param \App\Models\Note $note
     * @param Int $order
     * @return \Illuminate\View\View
     */
    public function store_textbox(Request $request){

        return 'store_textbox';
    }


    # テキストボックス基本情報編集ページの表示(edit_textbox)
    # テキストボックス基本情報の更新(update_textbox)

    # ノートの削除(destroy_textbox)
}
