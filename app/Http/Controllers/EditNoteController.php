<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateNoteFomeRequest;

use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\Tag;
use App\Models\Color;


class EditNoteController extends Controller
{
    /**
     * ノート編集ページの表示(edit_note)
     *
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function edit_note(Note $note){

        $form = "edit_note";

        return view('edit.note',compact('note') );
    }








    /**
     * ノート新規作成ページの表示(createedit_note_title)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function createedit_note_title(User $mypage_master){

        // $form = "createedit_note_title";
        $note = 0;
        $selects = [
            'colors' => Color::get(),
            'tags' => Tag::tagsList($mypage_master),
        ];
        $selects['colors'][0]->selected = 1;
        $selects['tags'][1]->checked = 1;

        // dd($selects['tags']);

        return view('edit.note_title',compact('note','mypage_master','selects') );

    }


    /**
     * 新規作成ノートの保存(store_note_title)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function store_note_title(Request $request){


        # タグの処理
        $tags_srting = implode(' ',$request->tags);
        $tags_srting =  str_replace('　',' ',$tags_srting); //新しいタグ入力の区切り文字を'半角空白'に統一
        $tags_array = explode(' ',$tags_srting);
        $tags_array = array_unique($tags_array); //重複したタグの入力を削除

        $key = array_search(null, $tags_array);
        if( ($key==true)or($key===0) ){ unset($tags_array[$key]);} // 'NULLの値'を削除

        $update_tags_srting = "'".implode("','",$tags_array)."'"; // ノートに保存する形式にタグを変換


        # 公開設定の処理
        $update_publishing = $request->publishing? 1:0; //'公開'=> 1, '非公開'=> 0,




        # 新規ノートの保存
        $note = new Note([
            'title' => $request->title,
            'color' => $request->color,
            'tags' => $update_tags_srting,
            'publishing' => $update_publishing,
            'user_id' => $request->mypage_master_id,
        ]);
        // $note->save();




        # 新しいタグの保存
        $update_tags_array = explode(",",$update_tags_srting); //'入力された全てのタグ'を配列形式に変換
        $old_tags =  Tag::where('user_id',$request->mypage_master_id )->get();// '登録済みのタグ'を取得
        foreach ($old_tags as $old_tag)
        {
            $del_val = $old_tag->value;
            $key = array_search($del_val, $update_tags_array);
            if( ($key==true)or($key===0) ){ unset($update_tags_array[$key]);} // '入力された全てのタグ'から'登録済みのタグ'を削除
        }


        //新しいタグが存在すれば、新しいタグを登録
        if( count($update_tags_array) )
        {

            foreach ($update_tags_array as $update_tag)
            {
                $tag = new Tag([
                    'value' => $update_tag,
                    'text' => str_replace("'",'',$update_tag),
                    'user_id' => $request->mypage_master_id,
                ]);
                $tag->save();

            }
        }
        dd($tag);

        return <<<__
        <h1>store_note_title</h1>
        <strong>mypage_master_id:</strong> $request->mypage_master_id <br>
        <strong>title:</strong> $request->title <br>
        <strong>color:</strong> $request->color <br>
        <strong>tags:</strong> $update_tags_srting<br>
        <strong>publishing:</strong> $update_publishing<br>
        __;

    }






    /**
     * ノート基本情報編集ページの表示(edit_note_title)
     *
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function edit_note_title(Note $note){

        # マイページ管理者
        $mypage_master = User::find($note->user_id);


        # フォーム入力の選択要素の取得
        $selects = [
            'colors' => Color::get(),
            'tags' => Tag::tagsList($mypage_master),
        ];


        // カラーの選択要素に、ノートの値を反映
        for ($i=0; $i < count($selects['colors']); $i++)
        {
            if( $selects['colors'][$i]->value == $note->color)
            {
                $selects['colors'][$i]->selected = 1;
                break;
            }
        }


        // タグの選択要素に、ノートの値を反映
        $note_tags = explode(',',$note->tags);
        for ($i=0; $i < count($selects['tags']); $i++)
        {
            foreach ($note_tags as $note_tag)
            {
                if( $selects['tags'][$i]->value == $note_tag)
                {
                    $selects['tags'][$i]->checked = 1;
                }
            }
        }


        return view('edit.note_title',compact('note','mypage_master','selects') );

    }


    /**
     * ノート基本情報の更新(update_note_title)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function update_note_title(Request $request, Note $note){

        return 'update_note_title';
    }

}
