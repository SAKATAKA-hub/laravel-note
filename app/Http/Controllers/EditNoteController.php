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


class EditNoteController extends Controller
{
    /**
     * ノート編集ページの表示(edit_note)
     *
     *
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function edit_note($note){

        # notesテーブルとtextboxsテーブルのリレーション
        $note = Note::with('Textboxes')->find($note);


        # マイページ管理者
        $mypage_master = User::find($note->user_id);


        return view('edit.note',compact('mypage_master','note') );
    }








    /**
     * ノート新規作成ページの表示(create_note_title)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function create_note_title(User $mypage_master){

        $note = 0;
        $selects = [
            'colors' => Color::get(),
            'tags' => Tag::tagsList($mypage_master),
        ];

        return view('edit.note_title',compact('note','mypage_master','selects') );

    }


    /**
     * 新規作成ノートの保存(store_note_title)
     *
     *
     * @param \Illuminate\Http\EditNoteTitleFormRequest $request
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function store_note_title(EditNoteTitleFormRequest $request, User $mypage_master){

        # 新規ノートの保存
        $note = new Note([
            'title' => $request->title, //タイトル
            'color' => $request->color, //カラー
            'tags' => $this::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'created_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //作成日時
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => $this::getPublicationAt($request), //公開日時

        ]);
        $note->save();


        # 新しいタグをtagsテーブルに保存
        $this::saveNewTags($request);


        return redirect()->route('edit_note',$note)
        ->with('note_alert','store_note_title');

    }






    /**
     * ノート基本情報編集ページの表示(edit_note_title)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function edit_note_title( User $mypage_master, $note){

        # notesテーブルとtextboxsテーブルのリレーション
        $note = Note::with('Textboxes')->find($note);


        # マイページ管理者
        $mypage_master = User::find($note->user_id);


        # '別のテキストボックスを編集する'ボタンの表示に利用
        $order = 'edit_note_title';


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


        return view('edit.note_title',compact('note','mypage_master','selects','order') );

    }


    /**
     * ノート基本情報の更新(update_note_title)
     *
     *
     * @param \Illuminate\Http\EditNoteTitleFormRequest $request
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function update_note_title(EditNoteTitleFormRequest $request, $note){

        # note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
        $note = Note::find($note);

        # ノート基本情報の更新
        $note->update([
            'title' => $request->title, //タイトル
            'color' => $request->color, //カラー
            'tags' => $this::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => $this::getPublicationAt($request), //公開日時
        ]);


        # 新しいタグをtagsテーブルに保存
        $this::saveNewTags($request);

        # 利用されていないタグをtagsテーブルから削除
        $this::deleteTags($note->user_id);


        return redirect()->route('edit_note',$note)
        ->with('note_alert','update_note_title');


    }





    /**
     * ノートの削除(destroy_note_title)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $mypage_master (マイページの管理者:マイページ管理者のチェックに利用)
     * @return \Illuminate\View\View
     */
    public function destroy_note_title(Request $request, User $mypage_master){

        # 削除するノート
        $note = Note::find($request->note_id);

        # マイページ管理者
        $mypage_master = $note->user_id;

        # 指定されたnoteに関連するS3保存画像の削除
        $this::deleteNoteImages($note);

        # ノートの削除
        $note->delete();

        # 利用されていないタグをtagsテーブルから削除
        $this::deleteTags($mypage_master);


        return redirect()->route('mypage_top',$mypage_master)
        ->with('note_alert','destroy_note_alert');
    }







    /*
    |
    |　コントローラー内で利用するメソッド
    |
    |
    */





    /**
     * タグの配列をノートの新規作成・更新時に保存する形式で返す(getUpdateTagsString)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public function getUpdateTagsString($request_tags)
    {
        $tags_srting = implode(' ',$request_tags);
        $tags_srting =  str_replace('　',' ',$tags_srting); //新しいタグ入力の区切り文字を'半角空白'に統一
        $tags_array = explode(' ',$tags_srting);
        $tags_array = array_unique($tags_array); //重複したタグの入力を削除

        $key = array_search(null, $tags_array);
        if( ($key==true)or($key===0) ){ unset($tags_array[$key]);} // 'NULLの値'を削除

        $update_tags_srting = "'".implode("','",$tags_array)."'"; // ノートに保存する形式にタグを変換

        return $update_tags_srting;
    }




    /**
     * 新しいタグをtagsテーブルに保存(saveNewTags)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public function saveNewTags($request)
    {
        # '入力された全てのタグ'から'登録済みのタグ'を削除
        $update_tags_srting = $this::getUpdateTagsString($request->tags);
        $update_tags_array = explode(",",$update_tags_srting); //'入力された全てのタグ'を配列形式に変換
        $old_tags =  Tag::where('user_id',$request->mypage_master_id )->get();// '登録済みのタグ'を取得
        foreach ($old_tags as $old_tag)
        {
            $del_val = $old_tag->value;
            $key = array_search($del_val, $update_tags_array);
            if( ($key==true)or($key===0) ){ unset($update_tags_array[$key]);} // '入力された全てのタグ'から'登録済みのタグ'を削除
        }


        # 新しいタグが存在すれば、新しいタグを登録
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
    }




    /**
     * 利用されていないタグをtagsテーブルから削除(deleteTags)
     *
     *
     * @param String $mypage_master_id
     * @return Array
     */
    public function deleteTags($mypage_master_id)
    {
        $tags =  Tag::where('user_id',$mypage_master_id)->get();// '登録済みのタグ'を取得
        foreach ($tags as $tag)
        {
            $mypage_master = User::find($mypage_master_id);
            $count = Note::TagsListCount($mypage_master,$tag->value); //タグが利用される投稿数

            if($count === 0){ $tag->delete();} //タグが利用される投稿数が0なら、そのタグを削除
        }

    }




    /**
     * ノートの公開日時(getPublicationAt)
     *
     * 公開設定がONのとき、今の日時を返す。
     * 公開設定がoffかつ、公開予約日時が指定されているとき、指定予約日時を返す。
     * それ以外はnull。
     *
     * @param \Illuminate\Http\Request $request
     * @return String
     */
    public function getPublicationAt($request)
    {
        return

            //公開設定がONのとき
            isset($request->publishing)? \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'):

            //公開設定がoff、公開予約日時が指定されているとき
            ( isset($request->release_datetime)? str_replace('T',' ',$request->release_datetime).':00' :null )

        ;

    }




    /**
     * 指定されたnoteに関連する投稿画像の削除(deleteNoteImages)
     *
     *
     * @param \App\Models\Note $note
     */
    public static function deleteNoteImages($note)
    {
        # 指定されたnoteに関連する画像関係のテキストボックス($image_text_boxes)の取得
        $textbox = new Textbox;
        $image_text_boxes = $textbox->where('note_id',$note->id)
        ->where( function($textbox){
            $textbox->where('textbox_case_id',10)->orWhere('textbox_case_id',11);
        })
        ->get();


        # 画像の削除処理
        foreach ($image_text_boxes as $image_text_box)
        {
            // 削除する画像のS3内のパス
            $delete_image_path = $image_text_box->main_value;

            // S3から画像を削除(EditTextboxControllerのメソッドを利用)
            EditTextboxController::deleteImage($delete_image_path);
        }
    }






}
