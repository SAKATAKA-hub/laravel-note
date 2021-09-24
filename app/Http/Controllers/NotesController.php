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
     * @param string $list_type (リストの表示タイプ ['seach_title' or 'tag' or 'month'])
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
        switch ($list_type) {
            case 'seach_title':
                # リストの検索値を代入
                $seach_value = $request->seach_value;

                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('title','like',"%".$seach_value."%")->paginate(8);
                break;

            //
            case 'tag':

                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('tags','like',"%".$seach_value."%")->paginate(8);
                break;

            //
            case 'month':
                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('created_at','like',$seach_value."%")->paginate(8);
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
