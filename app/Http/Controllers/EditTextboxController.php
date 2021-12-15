<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditTextboxFormRequest;

use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\TextboxCase;

use Illuminate\Support\Facades\Storage;


class EditTextboxController extends Controller
{
    /**
     * テキストボックス新規作成ページの表示(create_textbox)
     *
     *
     * @param Int $note
     * @param Int $order
     * @return \Illuminate\View\View
     */
    public function create_textbox($note, $order){

        # notesテーブルとtextboxsテーブルのリレーション
        $note = Note::with('Textboxes')->find($note);


        # マイページ管理者
        $mypage_master = User::find($note->user_id);


        # テキストボックスの種類を選択する要素のデータ
        $select_textbox_cases = TextboxCase::All();


        return view('edit.textbox',compact('mypage_master','note', 'order', 'select_textbox_cases'));

    }





    /**
     * 新規作成テキストボックスの保存(store_textbox)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function store_textbox(EditTextboxFormRequest $request, $note){

        # 編集ノート
        $note = Note::find($note);

        # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->textbox_case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' => $request->order, //採番
        ];



        # 採番の更新 (挿入するテキストボックスより後のテキストボックスの採番を'1'加算)
        $textboxes = Textbox::changeOrders($request, $note);

        for ($i=0; $i < count($textboxes); $i++)
        {

            $textboxes[$i]->update(['order' => $request->order +$i +1]); //挿入するテキストボックスの採番＋'$i-1'

        }

        # テキストが100文字以上の時、S3に保存
        $save_data = $this::uploadText($request,$save_data,$textbox=null);


        # 画像アップロード処理
        if($upload_image = $request->file('image'))
        {
            $save_data['main_value'] = $this::uploadImage($request); //画像のパスを'main_value'カラムに保存
        }


        # テキストボックスの保存
        $textbox = new Textbox($save_data);
        $textbox->save();


        # ノートの更新日の更新
        $note->update([
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'),
        ]);


        return redirect()->route('edit_note',$note)
        ->with('note_alert','store_textbox');


    }




    /**
     * テキストボックス編集ページの表示(edit_textbox)
     *
     *
     * @param Int $note (マイページ管理者のチェックに利用)
     * @param App\Models\Textbox $textbox
     * @return \Illuminate\View\View
     */
    public function edit_textbox($note, Textbox $textbox){

        # 編集中のテキストボックス(変数名変更)
        $edit_textbox = $textbox;


        # ノートデータ(リレーション)
        $note = Note::with('Textboxes')->find($edit_textbox->note_id);


        # マイページ管理者
        $mypage_master = User::find($note->user_id);


        # 編集するテキストボックスの種類
        $edit_textbox_case = TextboxCase::find($edit_textbox->textbox_case_id);


        # 採番
        $order =$edit_textbox->order;


        # テキストボックスの種類を選択する要素のデータ
        $select_textbox_cases = TextboxCase::All();


        return view('edit.textbox',compact(
            'mypage_master','note','order','edit_textbox','edit_textbox_case','select_textbox_cases'
        ));

    }


    /**
     * テキストボックスの更新(update_textbox)
     *
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param Int $note(マイページ管理者のチェックに利用)
     * @param App\Models\Textbox $edit_textbox　(編集中のテキストボックス)
     * @return \Illuminate\View\View
     */
    public function update_textbox(EditTextboxFormRequest $request, $note, Textbox $edit_textbox){

        # ノートデータ
        $note = Note::find($note);


        # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->textbox_case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' => $request->order, //採番
        ];




        # テキストが100文字以上の時、S3に保存
        $save_data = $this::uploadText($request,$save_data,$edit_textbox);

        # S3保存でなくなった時、S3ファイルの削除
        $edit_textbox_group = TextboxCase::find($edit_textbox->textbox_case_id)->group;
        $save_data_group = TextboxCase::find($save_data['textbox_case_id'])->group;
        $path = $edit_textbox->main_value;

        if(
            ($edit_textbox_group === 'text')&&
            ($edit_textbox->sub_value === 'S3_upload')&&
            ( ($save_data_group !== 'text') || ($save_data['sub_value'] === null) )&&
            (Storage::disk('s3')->exists($path))
        ){
            Storage::disk('s3')->delete($path); //削除
        }


        # 画像アップロード処理
        if($request->file('image')) //ファイルの添付があれば、アップロード
        {
            $save_data['main_value'] = $this::uploadImage($request); //画像のパスを'main_value'カラムに保存
        }
        elseif($request->old_image) //アップ―ド画像に変更が無ければ、画像パスを更新しない。
        {
            $save_data['main_value'] = $request->old_image;
        }


        # 画像の削除(テキストボックスの種類グループが、'image'からそれ以外に変更されるとき)
        $new_group = TextboxCase::find( $save_data['textbox_case_id'] )->group; //'編集前'のテキストボックスの種類グループ名
        $old_group = TextboxCase::find( $edit_textbox->textbox_case_id )->group; //'編集後'のテキストボックスの種類グループ名
        if ( ($old_group === 'image')&&($new_group !== 'image') )
        {
            $this::deleteImage( $edit_textbox->main_value );
        }


        # テキストボックスの更新
        $edit_textbox->update($save_data);


        # ノートの更新日の更新
        $note->update([
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'),
        ]);



        return redirect()->route('edit_note',$note)
        ->with('note_alert','update_textbox');


    }




    /**
     * テキストボックスの削除(destroy_textbox)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function destroy_textbox(Request $request, $note){

        # 削除するテキストボックスのノート
        $note = Note::find($note);


        # 削除するテキストボックスの取得
        $textbox = Textbox::find($request->textbox_id);


        # S3テキストの削除
        $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;
        $path = $textbox->main_value;
        if(
            ($textbox_group === 'text')&&
            ($textbox->sub_value === 'S3_upload')&&
            (Storage::disk('s3')->exists($path))
        ){
            Storage::disk('s3')->delete($path); //削除
        }



        # 画像の削除
        $pus = str_replace(["\r\n", "\r", "\n"], '', $textbox->main_value); //改行の削除
        $this::deleteImage( $pus );

        # 採番の更新 (削除するテキストボックスより後のテキストボックスの採番を'1'減算)
        $textboxes = Textbox::changeOrders($request, $note);

        for ($i=0; $i < count($textboxes); $i++)
        {
            $textboxes[$i]->update(['order' => $request->order +$i-1]); //削除するテキストボックスの採番＋'$i-1'

        }



        # テキストボックスの削除
        $textbox->delete();


        return redirect()->route('edit_note',$note)
        ->with('note_alert','destroy_textbox');

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


    /**
     * S3から画像を削除(deleteImage)
     *
     *
     * @param $delete_image_path (削除する画像のS3内のパス)
     */
    public static function deleteImage($delete_image_path)
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
        $textbox_group = TextboxCase::where('value',$request->textbox_case_name)->first()->group;

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


}
