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
        // $request['main_color'] = 'blue';
        // $array = $request->only('title','main_color','publishing');
        // dd($array);

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
        $tags_text = implode(', #', array_merge($tags,$new_tags) );
        $tags_text = '#'.$tags_text.',';

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
        $dir = 'upload/main_image'; //保存先ディレクトリ名

        if($request_file = $request->file('image'))
        {
            $num = Note::orderBy('id','desc')->first()->id +1; //投稿番号
            $extension = $request_file->extension(); //拡張子
            $file_name = sprintf('%04d_%06d', $request->user_id, $num).'.'.$extension; //ファイル

            $main_image_path = $request_file->storeAs($dir,$file_name); //画像の保存
        }
        else
        {
            $main_image_path = null;
        }


        # ノートの保存
        $request['tags'] = $tags_text;
        $request['main_image'] = $main_image_path;

        $note = new Note(
            $request->only('title','user_id','main_color','tags','main_image')
        );
        $note->save();

        return redirect()->route('mypage.list',$request->user_id);

        // return redirect()->route('edit_note',$note);
    }




    /**
     * ノートの削除(destroy_note)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\Http\Response
     */
    public function destroy_note(Note $note)
    {
        # 利用されていないタグの削除処理
        //タグを変換（文字列 ---> 配列）
        $tags_text = $note->tags;
        $tags_text = str_replace('#','',$tags_text);
        $tags_text = str_replace(',','',$tags_text);
        $tags = explode(' ',$tags_text);

        //タグ(#タグ,)を利用している投稿が他に存在しないとき、タグを一覧から削除
        foreach ($tags as $tag)
        {
            $count = Note::countTagUsed($note->user_id, $tag);
            if($count == 1)
            {
                Tag::where('user_id',$note->user_id)->where('tag',$tag)
                ->delete();
            }
        }


        # 画像の削除処理
        if( !empty($note->main_image) ){
            Storage::delete($note->main_image); //画像があれば削除
        }

        # 投稿の削除
        $note->delete();


        return redirect()->route('mypage.list',$note->user_id);
    }



    /**
     * ノート編集ページの表示(edit_note)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function edit_note(Note $note)
    {
        // return 'edit_note'.$note->id;
        return view('mypage.edit_note');
    }

}
