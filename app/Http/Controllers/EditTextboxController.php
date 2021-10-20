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
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function store_textbox(EditTextboxFormRequest $request, Note $note){

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


        # 画像アップロード処理
        if($upload_image = $request->file('image'))
        {
            $save_data['main_value'] = $this::uploadImage($request,$edit_textbox = null); //画像のパスを'main_value'カラムに保存
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
     * @param App\Models\Textbox $textbox
     * @return \Illuminate\View\View
     */
    public function edit_textbox(Textbox $textbox){

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
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param App\Models\Textbox $edit_textbox　(編集中のテキストボックス)
     * @return \Illuminate\View\View
     */
    public function update_textbox(EditTextboxFormRequest $request, Textbox $edit_textbox){

        # ノートデータ
        $note = Note::find($edit_textbox->note_id);


        # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->textbox_case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' => $request->order, //採番
        ];


        # 画像アップロード処理
        if($request->file('image')) //ファイルの添付があれば、アップロード
        {
            $save_data['main_value'] = $this::uploadImage($request,$edit_textbox); //画像のパスを'main_value'カラムに保存
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
            Storage::delete( $edit_textbox->main_value );
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
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function destroy_textbox(Request $request, Note $note){

        # 削除するテキストボックスの取得
        $textbox = Textbox::find($request->textbox_id);


        # 画像の削除
        $pus = str_replace(["\r\n", "\r", "\n"], '', $textbox->main_value); //改行の削除
        if ( Storage::disk('public')->exists($pus) )
        {
            Storage::delete($textbox->main_value);
        }

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
     * 画像のアップロード(uploadImage)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param App\Models\Textbox $edit_textbox　(編集中のテキストボックス)
     * @return String $image_path; //アプロードした画像のパスを返す
     */
    public function uploadImage($request,$edit_textbox)
    {
        $upload_image = $request->file('image');

        $dir = 'upload/textbox_img'; //アップロード先ディレクトリ名

        $new_id = !isset($edit_textbox)? Textbox::orderBy('id','desc')->first()->id +1//テキストボックスのID(新規)
        : $edit_textbox->id; //テキストボックスのID(編集)

        $extension = $upload_image->extension(); //拡張子

        $file_name = sprintf('%06d', $new_id).'.'.$extension; //ファイル名

        $image_path = $upload_image->storeAs($dir,$file_name); //画像のアップロード


        return $image_path;

    }
}
