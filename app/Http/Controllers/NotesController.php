<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreateNoteFomeRequest;

use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\Tag;

class NotesController extends Controller
{
    public function test(User $mypage_master, $seach_key=null){

        #(example) $seach_key = "tags=['Laravel','アプリ開発','学習ノート']";


        # 設設定
        $pn = 8; //ページネーションの表示ページ数
        $notes=[];

        # ノート一覧データの取得
        // 全てのノートを取得
        if ($seach_key == null)
        {
            $notes = ( Auth::user() && ($mypage_master->id == Auth::id()) ) ? //マイページの管理者がログインしているかどうか。
                Note::where('user_id',$mypage_master->id)->paginate($pn) : //非公開データを含む
                Note::where('user_id',$mypage_master->id)->where('publishing',1) //非公開データを含まない
                ->paginate($pn)
            ;
        }

        // 'タグ'の検索結果を取得
        elseif( substr($seach_key,0,4) == 'tags' )
        {
            $notes =  ( Auth::user() && ($mypage_master->id == Auth::id()) ) ?
                Note::seacrhTags($seach_key,$mypage_master)->paginate($pn) :
                Note::seacrhTags($seach_key,$mypage_master)->where('publishing',1)
                ->paginate($pn)
            ;

        }



        return view('test.test',compact('notes'));
    }




    /**
     * マイページの表示(list)
     *
     * @param \App\Models\User $mypage_master
     * @param string $seach_keys
     * @return \Illuminate\View\View
     */
    public function list( User $mypage_master, $seach_key=null )
    {

        // タイトル
        $title = $mypage_master->name.'さんのマイページ';

        // // ログイン中かつ、自分のマイページの表示処理
        // if( Auth::user() && ($mypage_master->id == Auth::id()) )
        // {
        //     $notes = Note::where('user_id',$mypage_master->id)->paginate(5);
        // }
        // // それ以外の処理(非公開ノートの非表示)
        // else
        // {
        //     $notes = Note::where('user_id',$mypage_master->id)->where('publishing',1)->paginate(5);
        // }


        // ログイン中かつ、自分のマイページの表示処理
        if( !( Auth::user() && ($mypage_master->id == Auth::id()) ) )
        {
            $notes = Note::where('user_id',$mypage_master->id)->where('publishing',1)->paginate(5);
        }
        // それ以外の処理(非公開ノートの非表示)
        else
        {
            $notes = Note::where('user_id',$mypage_master->id)->paginate(5);

        }



        return view('notes.list',compact('title','mypage_master','notes'));
    }




    /**
     * ノート閲覧ページの表示(show)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function show(Note $note){

        // ノートの管理者
        $mypage_master = User::find($note->user_id);


        return view('notes.show',compact('mypage_master','note') );
    }




}
