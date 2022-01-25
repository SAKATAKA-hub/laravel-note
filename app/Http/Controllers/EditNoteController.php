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
            'tags' => Method::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'created_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //作成日時
            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => Method::getPublicationAt($request, $note=NULL), //公開日時

        ]);
        $note->save();


        # 新しいタグをtagsテーブルに保存
        Method::saveNewTags($request);


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
            'tags' => Method::getUpdateTagsString($request->tags), //タグ('****','****','****'形式)
            'user_id' => $request->mypage_master_id, //投稿者ID

            'updated_at' => \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'), //更新日時
            'publication_at' => Method::getPublicationAt($request, $note), //公開日時
        ]);


        # 新しいタグをtagsテーブルに保存
        Method::saveNewTags($request);

        # 利用されていないタグをtagsテーブルから削除
        Method::deleteTags($note->user_id);


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




}
