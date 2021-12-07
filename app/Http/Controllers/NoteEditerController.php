<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditNoteTitleFormRequest;

use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\TextboxCase;
use App\Models\Tag;
use App\Models\Color;


class NoteEditerController extends Controller
{
    /**
     * ノート編集ページの表示(note_editer)
     *
     *
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function note_editer(Note $note){


        # マイページ管理者
        $mypage_master = User::find($note->user_id);

        # テキストボックスの種類を選択する要素のデータ
        $select_textbox_cases = TextboxCase::All();

        $order = 1;

        return view('note_editer.edit_note',
            compact('mypage_master','note')
        );

    }




    /**
     * 編集用のノートのjsonデータを返す。(json_note)
     *
     * @param Int $note
     */
    public function json_note(Request $request, Note $note)
    {

        # マイページ管理者
        $mypage_master = User::find($request->mypage_master_id);


        # ノートの基本情報の追加データ
        $note['chake_publishing'] = $note->chake_publishing;
        $note['time_text'] = $note->time_text;
        $note['tags_array'] = $note->tags_array;


        # textboxes情報の取得
        $textboxes = Textbox::where('note_id',$note->id)->orderBy('order','asc')->get();
        for ($i=0; $i < count($textboxes); $i++)
        {
            $textbox = $textboxes[$i];


            // textboxの種類データ
            $case = TextboxCase::find( $textbox->textbox_case_id );

            // データの追加
            $textbox['mode'] = 'select_textbox';
            $textbox['replace_main_value'] = $textbox->replace_main_value;
            $textbox['image_url'] = $textbox->image_url;
            $textbox['group'] = $case->group;
            $textbox['case_name'] = $case->value;
        }


        # textboxes配列の先頭にnoteの基本情報を追加
        $array = [];
        $array[] = ['mode'=>'select_textbox'];
        foreach ($textboxes as $textbox) {
            $array[] = $textbox;
        }
        $textboxes = $array;


        # JSONデータを返す
        return [
            'note' => $note,
            'textboxes' => $textboxes,
            'selects' => [
                'colors' => Color::get(),
                'tags' => Tag::tagsList($mypage_master),
                'textbox_cases' => TextboxCase::All(),
            ],


        ];
    }

}
