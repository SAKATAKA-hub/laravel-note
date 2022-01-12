<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\TextboxCase;
use App\Models\Tag;
use App\Models\Color;


class NoteEditerController extends Controller
{

    /**
     * ノート新規作成ページの表示(create_note)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function create_note(User $mypage_master)
    {
        # note情報(新規作成なので 0)
        $note = 0;

        return view('note_editer.edit_note',
            compact('mypage_master','note')
        );

    }


    /**
     * 新規作成ノートの保存(post_note)
     *
     *
     * @param \Illuminate\Http\EditNoteTitleFormRequest $request
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function post_note(Request $request, User $mypage_master){

        # 新規ノートの保存
        $note = new Note([
            'title' => $request->title, //タイトル
            'color' => $request->color, //カラー
            'tags' => Method::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'created_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //作成日時
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => Method::getPublicationAt($request), //公開日時
        ]);
        $note->save();


        # 新しいタグをtagsテーブルに保存
        Method::saveNewTags($request);


        return redirect()->route('note_editer',$note)
        ->with('note_alert','store_note_title');

    }




    /**
     * ノート基本情報の更新(update_note)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
     */
    public function update_note(Request $request, $note)
    {
        # note情報の取得
        $note = Note::find($note);


        # ノート基本情報の更新
        $note->update([
            'title' => $request->title, //タイトル
            'color' => $request->color, //カラー
            'tags' => Method::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => Method::getPublicationAt($request), //公開日時
        ]);


        # 新しいタグをtagsテーブルに保存
        Method::saveNewTags($request);

        # 利用されていないタグをtagsテーブルから削除
        Method::deleteTags($note->user_id);


        return redirect()->route('note_editer',$note)
        ->with('note_alert','update_note_title');
    }




    /**
     * ノートの削除(destroy_note)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $mypage_master (マイページの管理者:マイページ管理者のチェックに利用)
     * @return \Illuminate\View\View
     */
    public function destroy_note(Request $request, User $mypage_master){


        # 削除するノート
        $note = Note::find($request->note_id);

        # マイページ管理者
        $mypage_master = $note->user_id;


        # ストレージアップロードファイルの削除
        $textboxes = Textbox::where('note_id',$note->id)->get();
        foreach ($textboxes as $textbox)
        {

            // ストレージ保存のテキストファイルを削除
            Method::deleteTextFile($textbox);

            // ストレージ保存の画像ファイルを削除
            Method::deleteImageFile($textbox);

        }


        # ノートの削除
        $note->delete();

        # 利用されていないタグをtagsテーブルから削除
        Method::deleteTags($mypage_master);


        return redirect()->route('mypage_top',$mypage_master)
        ->with('note_alert','destroy_note_alert');
    }






    /**
     * ノート編集ページの表示(note_editer)
     *
     *
     * @param Int $note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
     * @return \Illuminate\View\View
     */
    public function note_editer($note)
    {
        # note情報の取得
        $note = Note::find($note);

        # マイページ管理者
        $mypage_master = User::find($note->user_id);

        return view('note_editer.edit_note',
            compact('mypage_master','note')
        );

    }




    /**
     * ノート表示のjsonデータを返す。(json_note)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $mypage_master (マイページの管理者:マイページ管理者のチェックに利用)
     * @param Int $note(新規作成ページの値は0(Int型)なので、$noteパラメーターは全てInt型で統一)
     */
    public function json_note(Request $request, User $mypage_master, $note)
    {

        $note = Note::find($note);


        if(!$note) //ノートの新規作成用データ ----------------------------
        {
            $note = [];
            $textboxes = [['mode'=>'editing_textbox'],];

            /*
             * $textboxesの最初の値は、ノート情報の表示状態を保存している。
             * 状態を'編集中'(editing_textbox_textbox)としてデータを返す。
             *
            */

            # note情報の取得
            $keys = ['id','title','color','tags','user_id',
                'created_at','updated_at','publication_at',
                'chake_publishing','time_text',
            ];
            foreach ($keys as $key ) {
                $note[$key] = '';
            }
            $note['tags_array'] = [];

        }
        else //ノートの更新用データ ---------------------------------------
        {
            # note情報の取得

            # ノートの基本情報の追加データ
            $note['chake_publishing'] = $note->chake_publishing; //公開設定
            $note['release_datetime_value'] = $note->release_datetime_value; //公開予定日
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
                $textbox['main_value_input'] = $textbox->main_value_input;
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

        } //endif


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




    /**
     * 新規作成テキストボックスの保存(ajax_store_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
     * @return JSON
     */
    public function ajax_store_textbox(Request $request, $note)
    {
        $request->case_name = empty($request->case_name)? $request->textbox_case_name: $request->case_name;

        # note情報の取得
        $note = Note::find($note);


        // # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' =>$request->order, //採番
        ];


        # 採番の更新 (挿入するテキストボックスより後のテキストボックスの採番を'1'加算)
        $textboxes = Textbox::changeOrders($request, $note);

        for ($i=0; $i < count($textboxes); $i++)
        {
            $textboxes[$i]->update(['order' => $request->order +$i +1]); //挿入するテキストボックスの採番＋'$i-1'
        }


        # テキストが100文字以上の時、ストレージに保存
        $save_data = Method::uploadTextFile($request,$save_data,$textbox=null);


        # 画像ファイルをストレージに保存
        $save_data = Method::uploadImageFile($request ,$save_data, $textbox=null);


        # テキストボックスの保存
        $textbox = new Textbox($save_data);
        $textbox->save();


        # ノートの更新日の更新
        $note->update(['updated_at' => $textbox->created_at]);


        # 画像のテキストオックスを保存した時、リダイレクト
        if(( isset($request->group) )&&( $request->group == 'image' ) )
        {
            return redirect()->route('note_editer',$note);
        }


        # JSONデータを返す
        return ['id' => $textbox->id,];
    }




    /**
     * テキストボックスの更新(ajax_update_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
     * @return JSON
     */
    public function ajax_update_textbox(Request $request, $note)
    {
        # note情報の取得
        $note = Note::find($note);


        # 更新するテキストボックス
        $textbox = Textbox::find($request->id);


        # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' => $request->order, //採番
        ];


        # テキストが100文字以上の時、ストレージに保存
        $save_data = Method::uploadTextFile($request,$save_data,$textbox);


        # 画像ファイルをストレージに保存
        $save_data = Method::uploadImageFile($request ,$save_data, $textbox);


        # テキストボックスの更新
        $textbox->update($save_data);

        # ノートの更新日の更新
        $note->update(['updated_at' => $textbox->updated_at]);

        # 画像のテキストボックスを保存した時、リダイレクト
        if(( isset($request->group) )&&( $request->group == 'image' ) )
        {
            return redirect()->route('note_editer',$note);
        }

    }




    /**
     * テキストボックスの削除(ajax_destroy_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $note(ミドルウェア利用の為、$noteパラメーターは全てInt型で統一)
     * @return \Illuminate\View\View
     */
    public function ajax_destroy_textbox(Request $request,$note)
    {
        # note情報の取得
        $note = Note::find($note);


        # 削除するテキストボックスの取得
        $textbox = Textbox::find($request->id);


        # ストレージ保存のテキストファイルを削除
        Method::deleteTextFile($textbox);


        # ストレージ保存の画像ファイルを削除
        Method::deleteImageFile($textbox);


        # 採番の更新 (削除するテキストボックスより後のテキストボックスの採番を'1'減算)
        $textboxes = Textbox::changeOrders($request, $note);
        for ($i=0; $i < count($textboxes); $i++)
        {
            $textboxes[$i]->update(['order' => $request->order +$i-1]); //削除するテキストボックスの採番＋'$i-1'
        }


        # テキストボックスの削除
        $textbox->delete();

        # ノートの更新日の更新
        $note->update([
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'),
        ]);


    }






}
