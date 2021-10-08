<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\Tag;

use TCPDF;
use TCPDF_FONTS;

class NotesController extends Controller
{

    /**
     * マイページの表示(mypage_top)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function mypage_top(User $mypage_master )
    {
        # $list_type ('ノートリストの見出し'と、side contentsの'新着投稿リスト'に利用 )
        $list_type = '';


        # ノート一覧データの取得
        //(マイページの管理者がログインしていなければ、非公開ノートの非表示)
        $notes = Note::mypageNotes($mypage_master)->paginate(8);


        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];


        return view('notes.mypage_top',compact(
            'mypage_master','notes','side_lists','list_type'
        ));
    }




    /**
     * マイページの検索表示(mypage_seach)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @return \Illuminate\View\View
     */
    public function mypage_seach(Request $request, User $mypage_master)
    {
        # リストの表示タイプ ['seach_title' or 'tag' or 'month']
        $list_type = $request->list_type;

        # リストの検索値
        $seach_value = $request->seach_value;



        # ノート一覧データの取得
        //(マイページの管理者がログインしていなければ、非公開ノートの非表示)
        $notes = [];
        switch ($list_type) {

            case 'seach_title':
                #検索見出し
                $seach_heading = 'タイトルに”'. $seach_value. '”を含むノート一覧';

                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('title','like',"%".$seach_value."%")->paginate(8);
                break;

            //
            case 'tag':
                #検索見出し
                $seach_heading = 'タグ ”'. str_replace("'",'', $seach_value). '”を含むノート一覧';

                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('tags','like',"%".$seach_value."%")->paginate(8);
                break;

            //
            case 'month':
                #検索見出し
                $seach_heading = '投稿月'. $seach_value. 'のノート一覧';

                # ノート一覧データの取得
                $notes = Note::mypageNotes($mypage_master)
                ->where('created_at','like',$seach_value."%")->paginate(8);
                break;

            //
            default:

                return redirect()->route('mypage_top',$mypage_master);
            //
        }

        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];


        return view('notes.mypage_top',compact(
            'mypage_master','notes','side_lists','seach_heading'
        ));
    }




    /**
     * ノート閲覧ページの表示(note)
     *
     *
     * @param \App\Models\User $mypage_master (マイページの管理者)
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function note($note){

        # notesテーブルとtextboxsテーブルのリレーション
        $note = Note::with('Textboxes')->find($note);

        # マイページ管理者
        $mypage_master = User::find($note->user_id);

        # サイドコンテンツのリスト一式取得
        $side_lists = [
            'new_notes' => Note::mypageNotes($mypage_master)->limit(5)->get(),
            'tags' => Tag::tagsList($mypage_master),
            'months' => Note::monthsList($mypage_master),
        ];


        return view('notes.note',compact('mypage_master','note','side_lists') );
    }




    /**
     * ノート印刷ページの表示(print)
     *
     *
     * @param \App\Models\Note $note
     * @return \Illuminate\View\View
     */
    public function print($note){

        # notesテーブルとtextboxsテーブルのリレーション
        $note = Note::with('Textboxes')->find($note);

        # ノートの管理者
        $mypage_master = User::find($note->user_id);

        # CSSファイル内容の読み込み
        $css = file_get_contents( asset('css/layouts/note.css') );


        # PDF設定
        // ページ設定
        $pdf = new TCPDF("P", "mm", "A4", true, "UTF-8" );// PDF 生成メイン　－　A4 縦に設定
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // 日本語フォント設定
        $pdf->setFont('kozminproregular','',10);

        // ページ追加
        $pdf->addPage();

        // PDF プロパティ設定
        $pdf->SetTitle($note->title);  // PDFドキュメントのタイトルを設定
        $pdf->SetAuthor($mypage_master->name);  // PDFドキュメントの著者名を設定

        // HTMLを描画、viewの指定と変数代入 - pdf_test.blade.php
        $pdf->writeHTML(
            view('notes.print', compact('mypage_master','note','css') )->render()
        );

        // 出力指定 ファイル名、拡張子、I(ブラウザー表示)
        $pdf->output( sprintf('note%06d',$note->id).'.pdf', 'I' );
        return;
    }






}
