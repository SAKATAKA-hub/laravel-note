<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @param Note $note
     * @return \Illuminate\View\View
     */
    public function note_editer(Note $note){


        # マイページ管理者
        $mypage_master = User::find($note->user_id);

        return view('note_editer.edit_note',
            compact('mypage_master','note')
        );

    }




    /**
     * 編集用のノートのjsonデータを返す。(json_note)
     *
     * @param Note $note
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




    /**
     * 新規作成テキストボックスの保存(ajax_store_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Note $note
     * @return JSON
     */
    public function ajax_store_textbox(Request $request, Note $note)
    {

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


        // # 画像アップロード処理
        // if($upload_image = $request->file('image'))
        // {
        //     $save_data['main_value'] = $this::uploadImage($request); //画像のパスを'main_value'カラムに保存
        // }


        # テキストボックスの保存
        $textbox = new Textbox($save_data);
        $textbox->save();


        # ノートの更新日の更新
        $note->update(['updated_at' => $textbox->created_at]);


        # JSONデータを返す
        return ['id' => $textbox->id,];
    }




    /**
     * テキストボックスの更新(ajax_update_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Note $note
     * @return JSON
     */
    public function ajax_update_textbox(Request $request, Note $note)
    {
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



        // # 画像アップロード処理
        // if($request->file('image')) //ファイルの添付があれば、アップロード
        // {
        //     $save_data['main_value'] = $this::uploadImage($request); //画像のパスを'main_value'カラムに保存
        // }
        // elseif($request->old_image) //アップ―ド画像に変更が無ければ、画像パスを更新しない。
        // {
        //     $save_data['main_value'] = $request->old_image;
        // }


        // # 画像の削除(テキストボックスの種類グループが、'image'からそれ以外に変更されるとき)
        // $new_group = TextboxCase::find( $save_data['textbox_case_id'] )->group; //'編集前'のテキストボックスの種類グループ名
        // $old_group = TextboxCase::find( $textbox->textbox_case_id )->group; //'編集後'のテキストボックスの種類グループ名
        // if ( ($old_group === 'image')&&($new_group !== 'image') )
        // {
        //     $this::deleteImage( $textbox->main_value );
        // }


        # テキストボックスの更新
        $textbox->update($save_data);

        # ノートの更新日の更新
        $note->update(['updated_at' => $textbox->updated_at]);

    }




    /**
     * テキストボックスの削除(ajax_destroy_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Note $note
     * @return \Illuminate\View\View
     */
    public function ajax_destroy_textbox(Request $request, Note $note)
    {

        # 削除するテキストボックスの取得
        $textbox = Textbox::find($request->id);


        // // # 画像の削除
        // // $pus = str_replace(["\r\n", "\r", "\n"], '', $textbox->main_value); //改行の削除
        // // $this::deleteImage( $pus );

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
    |　コントローラー内で利用するメソッド
    |
    |
    */

    /**
     * S3に画像をアップロード(uploadImage)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @return String $path (アプロードした画像のs3内パスを返す)
     */
    public function uploadImage($request)
    {
        $upload_image = $request->file('image'); //保存画像
        $dir = 'upload_image'; //アップロード先ディレクトリ名
        $delete_image_path = isset($request->old_image)? $request->old_image : ''; //古いアップロード画像のパス

        # 画像のアップロード
        $path = Storage::disk('s3')->putFile($dir, $upload_image, 'public');

        # 古いアップロード画像の削除
        $this:: deleteImage($delete_image_path);


        return $path;
    }


}
