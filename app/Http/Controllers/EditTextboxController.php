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


    public function test(){return 'test';}

    /**
     * 新規作成テキストボックスの保存(store_textbox)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param Int $note
     * @return \Illuminate\View\View
     */
    public function store_textbox(EditTextboxFormRequest $request, $note){

        // return NoteEditerController::ajax_store_textbox($request, $note);




        # 編集ノート
        $note = Note::find($note);

        # 保存データ
        $save_data = [
            'note_id' => $note->id, //ノートID
            'textbox_case_id' => TextboxCase::where('value',$request->case_name)->first()->id, //テキストボックスの種類ID
            'main_value' => $request->main_value, //mein_value
            'sub_value' => $request->sub_value, //sub_value
            'order' => $request->order, //採番
        ];

        # テキストが100文字以上の時、ストレージに保存
        $save_data = Method::uploadTextFile($request,$save_data,$textbox=null);


        # 画像ファイルをストレージに保存
        $save_data = Method::uploadImageFile($request ,$save_data, $textbox=null);


        # 採番の更新 (挿入するテキストボックスより後のテキストボックスの採番を'1'加算)
        $textboxes = Textbox::changeOrders($request, $note);

        for ($i=0; $i < count($textboxes); $i++)
        {
            $textboxes[$i]->update(['order' => $request->order +$i +1]);
            //挿入するテキストボックスの採番＋'$i+1'
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


        $textbox = $edit_textbox;

        # ノートデータ
        $note = Note::find($note);


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


        return redirect()->route('edit_note',$note)
        ->with('note_alert','destroy_textbox');

    }

}
