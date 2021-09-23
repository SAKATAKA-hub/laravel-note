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


    public function test(User $mypage_master, $seach_mode=null, $seach_val=null){

        #(example) $seach_key = "tags=['Laravel','アプリ開発','学習ノート']";


        # 設定
        $notes=[];
        $pn = 8; //ページネーション最高表示数

        # ノート一覧データの取得
        // マイページ管理者の全てのノートを取得(マイページの管理者がログインしていなければ、非公開ノートの非表示)
        switch ($seach_mode) {
            case null:

                $notes = Note::mypageNotes($mypage_master)->paginate($pn);
                break;
            //
            default:
                // return back();
            //
        }




        # ノート一覧データの取得
        // 全てのノートを取得
        // if ($seach_mode == null)
        // {
        //     $notes = ( Auth::user() && ($mypage_master->id == Auth::id()) ) ? //マイページの管理者がログインしているかどうか。
        //         Note::where('user_id',$mypage_master->id)->paginate($pn) : //非公開データを含む
        //         Note::where('user_id',$mypage_master->id)->where('publishing',1) //非公開データを含まない
        //         ->paginate($pn)
        //     ;
        // }

        // // 'タグ'の検索結果を取得
        // elseif( substr($seach_key,0,4) == 'tags' )
        // {
        //     $notes =  ( Auth::user() && ($mypage_master->id == Auth::id()) ) ?
        //         Note::seacrhTags($seach_key,$mypage_master)->paginate($pn) :
        //         Note::seacrhTags($seach_key,$mypage_master)->where('publishing',1)
        //         ->paginate($pn)
        //     ;
        // }

        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(3)->get(),
            'tags' => Tag::where('user_id',$mypage_master->id)->get(),
            'months' => Note::monthsList($mypage_master->id),
        ];

        return view( 'test.test',compact('mypage_master','notes','side_lists'));
    }


















    /**
     * マイページの表示(list)
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @param string $seach_keys
     * @return \Illuminate\View\View
     */
    public function list( User $mypage_master )
    {
        # $list_type ('ノートリストの見出し'と、side contentsの'新着投稿リスト'に利用 )
        $list_type = '';

        # タイトル
        $title = $mypage_master->name.'さんのマイページ'; //('ページタイトル'、'パンくずリストタイトル'に利用)


        # ノート一覧データの取得
        //(マイページの管理者がログインしていなければ、非公開ノートの非表示)
        $notes = Note::mypageNotes($mypage_master)->paginate(8);


        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];


        return view('notes.list',compact(
            'title','mypage_master','notes','side_lists','list_type'
        ));
    }




    /**
     * マイページの検索表示(seach_list)
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @param string $list_type (リストの表示タイプ ['seach_word' or 'tag' or 'month'])
     * @param string $seach_value (リストの検索値)
     * @return \Illuminate\View\View
     */
    public function seach_list( Request $request, User $mypage_master, $list_type, $seach_value=null )
    {

        # タイトル
        $title = $mypage_master->name.'さんのマイページ';


        # ノート一覧データの取得
        //(マイページの管理者がログインしていなければ、非公開ノートの非表示)
        $notes = [];
        // Note::mypageNotes($mypage_master)->paginate(8);
        switch ($list_type) {
            case 'seach_word':
                //リストの検索値を代入
                $seach_value = $request->seach_value;

                break;
            //
            case 'tag':
                break;
            //
            case 'month':
                break;
            //
            default:
                return redirect()->route('list',$mypage_master);
            //
        }


        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];

        return view('notes.list',compact(
            'title','mypage_master','notes','side_lists','list_type','seach_value'
        ));
    }





    /**
     * ノート閲覧ページの表示(show)
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function show(Note $note){

        # ノートの管理者
        $mypage_master = User::find($note->user_id);

        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];

        return view('notes.show',compact('mypage_master','note','side_lists') );
    }




}
