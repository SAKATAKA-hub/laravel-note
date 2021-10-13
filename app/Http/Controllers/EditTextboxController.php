<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditTextboxFormRequest;

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
     * @param \App\Models\Note $note
     * @param Int $order
     * @return \Illuminate\View\View
     */
    public function create_textbox(Note $note, $order){

        # テキストボックスの種類を選択する要素のデータ
        $select_textbox_cases = TextboxCase::All();

        return view('edit.textbox',compact('note', 'order', 'select_textbox_cases'));

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
            $textboxes[$i]->update(['order' => $textboxes[$i]->order +1]); //採番を'1'加算
        }




        # 画像アップロード処理
        if($upload_image = $request->file('image'))
        {
            $dir = $this::uplordImageDir(); //アップロード先ディレクトリ名

            $new_id = Textbox::orderBy('id','desc')->first()->id +1; //新しいテキストボックスの採番

            $extension = $upload_image->extension(); //拡張子

            $file_name = sprintf('%06d', $new_id).'.'.$extension; //ファイル名

            $image_path = $upload_image->storeAs($dir,$file_name); //画像のアップロード

            $save_data['main_value'] = $image_path; //画像のパスを'main_value'カラムに保存
        }


        # テキストボックスの保存
        $textbox = new Textbox($save_data);
        $textbox->save();



        return redirect()->route('edit_note',$note);

    }




    /**
     * テキストボックス基本情報編集ページの表示(edit_textbox)
     *
     *
     * @param App\Models\Textbox $textbox
     * @return \Illuminate\View\View
     */
    public function edit_textbox(Textbox $textbox){

        // return'edit_textbox';

        # ノートデータ
        $note = Note::find($textbox->note_id);

        #
        $order =$textbox->order;

        # テキストボックスの種類を選択する要素のデータ
        $select_textbox_cases = TextboxCase::All();

        return view('edit.textbox',compact('note','order','textbox', 'select_textbox_cases'));

    }

    # テキストボックス基本情報の更新(update_textbox)





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
        $pus = str_replace(["\r\n", "\r", "\n"], '', $textbox->main_value); //改行の座駆除
        if ( Storage::disk('public')->exists($pus) )
        {
            Storage::delete($textbox->main_value);
        }

        # 採番の更新 (削除するテキストボックスより後のテキストボックスの採番を'1'減算)
        $textboxes = Textbox::changeOrders($request, $note);

        for ($i=0; $i < count($textboxes); $i++)
        {
            $textboxes[$i]->update(['order' => $textboxes[$i]->order -1]); //採番を'1'減算
        }



        # テキストボックスの削除
        $textbox->delete();


        return redirect()->route('edit_note',$note);
    }







    /*
    |
    |　コントローラー内で利用するメソッド
    |
    |
    */

    /**
     * 画像アップロード先ディレクトリ(uplordImageDir)
     *
     * @param \App\Http\Requests\FormFormRequest $request
     * @return String
     */
    public function uplordImageDir()
    {
        return 'upload/textbox_img';//アップロード先ディレクトリ
    }

}
