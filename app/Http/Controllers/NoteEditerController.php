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
            'tags' => $this::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'created_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //作成日時
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => $this::getPublicationAt($request), //公開日時
        ]);
        $note->save();


        # 新しいタグをtagsテーブルに保存
        $this::saveNewTags($request);


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
            'tags' => $this::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => $this::getPublicationAt($request), //公開日時
        ]);


        # 新しいタグをtagsテーブルに保存
        $this::saveNewTags($request);

        # 利用されていないタグをtagsテーブルから削除
        $this::deleteTags($note->user_id);


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


        # S3アップロードファイルの削除
        $textboxes = Textbox::where('note_id',$note->id)->get();
        foreach ($textboxes as $textbox)
        {

            # 指定されたnoteに関連するS3保存テキストファイルの削除
            $this::deleteTextFile($textbox);


            # 指定されたnoteに関連するS3保存画像の削除
            $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;
            $path = $textbox->main_value;
            if(
                ($textbox_group === 'image')&&
                (Storage::disk('s3')->exists($path))
            ){
                Storage::disk('s3')->delete($path); //削除
            }
        }


        # ノートの削除
        $note->delete();

        # 利用されていないタグをtagsテーブルから削除
        $this::deleteTags($mypage_master);


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
            $note = Note::find($note);

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


        # テキストが100文字以上の時、S3に保存
        $save_data = $this::uploadText($request,$save_data,$textbox=null);


        // # 画像アップロード処理
        if($upload_image = $request->file('image'))
        {
            $save_data['main_value'] = $this::uploadImageFile($request); //画像のパスを'main_value'カラムに保存
        }


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




        # テキストが100文字以上の時、S3に保存
        $save_data = $this::uploadText($request,$save_data,$textbox);


        # S3保存でなくなった時、S3ファイルの削除
        $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;
        $save_data_group = TextboxCase::find($save_data['textbox_case_id'])->group;
        $path = $textbox->main_value;

        if(
            ($textbox_group === 'text')&&
            ($textbox->sub_value === 'S3_upload')&&
            ( ($save_data_group !== 'text') || ($save_data['sub_value'] === null) )&&
            (Storage::disk('s3')->exists($path))
        ){

            Storage::disk('s3')->delete($path); //削除

        }
        // dd('srue');

        # 画像アップロード処理
        if($request->file('image')) //ファイルの添付があれば、アップロード
        {
            $save_data['main_value'] = $this::uploadImageFile($request); //画像のパスを'main_value'カラムに保存
        }
        elseif($request->old_image) //アップ―ド画像に変更が無ければ、画像パスを更新しない。
        {
            $save_data['main_value'] = $request->old_image;
        }


        # 画像の削除(テキストボックスの種類グループが、'image'からそれ以外に変更されるとき)
        $new_group = TextboxCase::find( $save_data['textbox_case_id'] )->group; //'編集前'のテキストボックスの種類グループ名
        $old_group = TextboxCase::find( $textbox->textbox_case_id )->group; //'編集後'のテキストボックスの種類グループ名
        if (
            ($old_group === 'image')&&
            ($new_group !== 'image')
        ){
            $this::deleteImageFile( $textbox->main_value );
        }



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


        # S3テキストの削除
        $this::deleteTextFile($textbox);


        # 画像の削除
        $pus = str_replace(["\r\n", "\r", "\n"], '', $textbox->main_value); //改行の削除
        $this::deleteImageFile( $pus );


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




    /*
    |
    | コントローラー内で利用するメソッド
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
        $tags_srting =  str_replace(' ',' ',$tags_srting); //新しいタグ入力の区切り文字を'半角空白'に統一
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
     * S3に画像をアップロード(uploadImageFile)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @return String $path (アプロードした画像のs3内パスを返す)
     */
    public function uploadImageFile($request)
    {
        $upload_image = $request->file('image'); //保存画像
        $dir = 'upload_image'; //アップロード先ディレクトリ名
        $delete_image_path = isset($request->old_image)? $request->old_image : ''; //古いアップロード画像のパス

        # 画像のアップロード
        $path = Storage::disk('s3')->putFile($dir, $upload_image, 'public');

        # 古いアップロード画像の削除
        $this:: deleteImageFile($delete_image_path);


        return $path;
    }




    /**
     * S3から画像を削除(deleteImageFile)
     *
     *
     * @param $delete_image_path (削除する画像のS3内のパス)
     */
    public function deleteImageFile($delete_image_path)
    {
        if ( Storage::disk('s3')->exists($delete_image_path) ) // 画像が存在するか確認
        {
            Storage::disk('s3')->delete($delete_image_path); //画像の削除
        }
    }




    /**
     * S3にテキストをアップロード(uploadText)
     * 100文字以上の'文章'については、S3にテキスト保存する。
     *
     * @param \Illuminate\Http\Request $request
     * @param Array $request
     * @return Array $save_data
     */
    public function uploadText($request,$save_data,$textbox)
    {
        # テキストボックスグループの取得
        $textbox_group = TextboxCase::where('value',$request->case_name)->first()->group;

        if( ($textbox_group==='text')&&( strlen($request->main_value)>99 ) )
        {
            # 基本設定
            $id = $textbox!==null? $textbox->id: Textbox::orderBy('id','desc')->first()->id +1;
            $dir = 'upload_text/';
            $file = sprintf('%06d',$id).'.txt';
            $text = $request->main_value;

            # S3にテキストファイルを保存
            Storage::disk('s3')->put($dir.$file, $text);

            # $save_dataに値を保存
            $save_data['main_value'] = $dir.$file;
            $save_data['sub_value'] = 'S3_upload';

        }

        return $save_data;

    }




    /**
     * S3からテキストファイルを削除(deleteTextFile)
     *
     *
     * @param App\Models\Textbox $textbox
     */
    public function deleteTextFile($textbox)
    {
        $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;
        $path = $textbox->main_value;
        if(
            ($textbox_group === 'text')&&
            ($textbox->sub_value === 'S3_upload')&&
            (Storage::disk('s3')->exists($path))
        ){
            Storage::disk('s3')->delete($path); //削除
        }
    }

}
