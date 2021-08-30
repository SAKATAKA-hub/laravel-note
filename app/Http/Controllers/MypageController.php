<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateNoteFomeRequest;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;

class MypageController extends Controller
{
    /**
     * ノート一覧ページの表示(list)
     *
     * @param \App\Models\User $user
     * @param string $seach_keys
     * @return \Illuminate\View\View
     */
    public function list( User $user, $seach_keys=null )
    {
        $notes = Note::where('user_id',$user->id)->get();

        return view('mypage.list',compact('user','notes'));
    }




    /**
     * ノートページの表示(show_note)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function show_note(Note $note){

        return view('mypage.show_note',compact('note'));
    }




    /**
     * ノートの新規作成ページの表示(create_note)
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function create_note(User $user)
    {
        $tags = Tag::where('user_id',$user->id)->get();

        return view('mypage.create_note',compact('user','tags') );
    }




    /**
     * 新規作成ノートの保存(store_note)
     *
     * @param \App\Http\Requests\CreateNoteFomeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store_note(CreateNoteFomeRequest $request)
    {
        # タグの処理
        //複数関連タグを一つの文字列に変換
        $tags = [];
        if(!empty($request->tags)){
            $tags = $request->tags;
        }
        $new_tags = [];
        if(!empty($request->new_tags)){
            $new_tags = str_replace('　',' ',$request->new_tags);
            $new_tags = explode(' ',$new_tags);
        }
        $tags_text = implode(',', array_merge($tags,$new_tags) );

        //新しいタグをテーブルに保存
        $old_tags =[];
        $items = Tag::where('user_id',$request->user_id)->get();
        foreach($items as $item){
            $old_tags[] = $item->tag;
        }
        $new_tags = array_diff($new_tags,$old_tags); //重複するタグを除去

        foreach ($new_tags as $new_tag)
        {
            $tag = new Tag([
                'tag' => $new_tag,
                'user_id' => $request->user_id,
            ]);
            $tag->save();
        }


        # 画像の処理




        # ノートの保存
        $note = new Note([
            'title' => $request->title,
            // 'main_image' => $request-,
            'main_color' => $request->main_color,
            'tags' => $tags_text,
            'user_id' => $request->user_id,
        ]);
        $note->save();

        return redirect()->route('mypage.list',$request->user_id);

        // return redirect()->route('edit_note',$note);
    }




    /**
     * ノート編集ページの表示(edit_note)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function edit_note(Note $note)
    {
        return 'edit_note'.$note;
        return view('mypage.edit_note');
    }




    # ファイルアップロードフォーム
    public function upload_form()
    {
        return view('upload_form');
    }

    # 画像のアップロード
    public function upload_file(Request $request)
    {
        if($request->file('upload_file'))
        {
            $extension = $request->file('upload_file')->extension(); //拡張子
            $file_name = 'upload_file'.'.'.$extension; //ファイル名
            $dir = 'upload'; //保存先ディレクトリ名

            //ファイル名(拡張子も含む)の自動生成と'upload'ディレクトリへファイルを保存
            $path = $request->file('upload_file')->store($dir);

            //任意のファイル名でファイルを保存
            $path = $request->file('upload_file')->storeAs($dir,$file_name);
            // $path = 'upload/upload_file.jpg';

            return $path;
        }
    }
}



