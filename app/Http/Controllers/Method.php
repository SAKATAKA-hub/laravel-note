<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Note;
use App\Models\Textbox;
use App\Models\TextboxCase;
use App\Models\Tag;
use App\Models\Color;



/**
 * --------------------------------------------
 * コントローラー内で共通利用するメソッドクラス
 * --------------------------------------------
*/
class Method
{
    /**
     * タグの配列をノートの新規作成・更新時に保存する形式で返す(getUpdateTagsString)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public static function getUpdateTagsString($request_tags)
    {
        $tags_srting = implode(' ',$request_tags);
        $tags_srting =  str_replace('　',' ',$tags_srting); //新しいタグ入力の区切り文字を'半角空白'に統一
        $tags_array = explode(' ',$tags_srting);
        $tags_array = array_unique($tags_array); //重複したタグの入力を削除

        $key = array_search(null, $tags_array);
        if( ($key==true)or($key===0) ){ unset($tags_array[$key]);} // 'NULLの値'を削除

        $update_tags_srting = "'".implode("','",$tags_array)."'"; // ノートに保存する形式にタグを変換

        return $update_tags_srting;
    }




    /**
     * 新しいタグをtagsテーブルに保存(saveNewTags)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public static function saveNewTags($request)
    {
        # '入力された全てのタグ'から'登録済みのタグ'を削除
        $update_tags_srting = self::getUpdateTagsString($request->tags);
        $update_tags_array = explode(",",$update_tags_srting); //'入力された全てのタグ'を配列形式に変換
        $old_tags =  Tag::where('user_id',$request->mypage_master_id )->get();// '登録済みのタグ'を取得
        foreach ($old_tags as $old_tag)
        {
            $del_val = $old_tag->value;
            $key = array_search($del_val, $update_tags_array);
            if( ($key==true)or($key===0) ){ unset($update_tags_array[$key]);} // '入力された全てのタグ'から'登録済みのタグ'を削除
        }


        # 新しいタグが存在すれば、新しいタグを登録
        if( count($update_tags_array) )
        {

            foreach ($update_tags_array as $update_tag)
            {
                $tag = new Tag([
                    'value' => $update_tag,
                    'text' => str_replace("'",'',$update_tag),
                    'user_id' => $request->mypage_master_id,
                ]);
                $tag->save();

            }
        }
    }




    /**
     * 利用されていないタグをtagsテーブルから削除(deleteTags)
     *
     *
     * @param String $mypage_master_id
     * @return Array
     */
    public static function deleteTags($mypage_master_id)
    {
        $tags =  Tag::where('user_id',$mypage_master_id)->get();// '登録済みのタグ'を取得
        foreach ($tags as $tag)
        {
            $mypage_master = User::find($mypage_master_id);
            $count = Note::TagsListCount($mypage_master,$tag->value); //タグが利用される投稿数

            if($count === 0){ $tag->delete();} //タグが利用される投稿数が0なら、そのタグを削除
        }

    }




    /**
     * ノートの公開日時(getPublicationAt)
     *
     * 公開設定がONのとき、今の日時を返す。
     * 公開設定がoffかつ、公開予約日時が指定されているとき、指定予約日時を返す。
     * それ以外はnull。
     *
     * @param \Illuminate\Http\Request $request
     * @return String
     */
    public static function getPublicationAt($request)
    {
        return

            //公開設定がONのとき
            isset($request->publishing)? \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s'):

            //公開設定がoff、公開予約日時が指定されているとき
            ( isset($request->release_datetime)? str_replace('T',' ',$request->release_datetime).':00' :null )

        ;

    }




    /**
     * 画像ファイルをアップロード(uploadImageFile)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param Array $save_data
     * @param App\Models\Textbox $textbox
     * @return Array $save_data
     */
    public static function uploadImageFile($request ,$save_data, $textbox)
    {
        # 画像ファイルが存在するとき(画像の挿入・更新)
        if( $upload_image = $request->file('image') )
        {
            // 画像のアップロード
            $dir = '/upload/img'; //アップロード先ディレクトリ名
            $save_data['main_value'] = $upload_image->store($dir);

            // 古いアップロード画像の削除(画像の更新時)
            if($request->old_image)
            {
                Storage::delete($request->old_image);
            }

        }

        # 画像ファイルが存在しないとき(画像はそのまま)
        elseif($request->old_image)
        {
            $save_data['main_value'] = $request->old_image;
        }

        # 画像の削除(テキストボックスの種類グループが、'image'からそれ以外に変更されるとき)
        if($textbox !== null)
        {
            $new_group = TextboxCase::find( $save_data['textbox_case_id'] )->group; //'編集後'のテキストボックスの種類グループ名
            $old_group = TextboxCase::find( $textbox->textbox_case_id )->group; //'編集前'のテキストボックスの種類グループ名
            if ( ($old_group === 'image')&&($new_group !== 'image') )
            {
                Storage::delete($textbox->main_value);
            }
        }


        return $save_data;
    }




    /**
     * 画像ファイルを削除(deleteImageFile)
     *
     * @param App\Models\Textbox $textbox (更新前のテキストボックスのデータ)
     */
    public static function deleteImageFile($textbox)
    {
        $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;

        if($textbox_group === 'image')
        {
            Storage::delete($textbox->main_value); //削除
        }

    }





    /**
     * テキストファイルをアップロード(uploadTextFile)
     * テキストが100文字以上の時、ストレージにテキストファイルで保存
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @param Array $save_data (新規挿入または更新するテキストボックスのデータ)
     * @param App\Models\Textbox $textbox (更新前のテキストボックスのデータ)
     * @return Array $save_data
     */
    public static function uploadTextFile($request,$save_data,$textbox)
    {
        // テキストボックスグループの取得
        $textbox_group = TextboxCase::where('value',$request->case_name)->first()->group;

        // return $save_data;

        /*
         更新前のテキストボックスの種類が'text'で、文章をテキストファイル保存しているとき、

         更新前のテキストファイルを削除
        */
        if($textbox !== null)
        {
            self::deleteTextFile($textbox);
        }


        /*
         更新するテキストボックスの種類が'text'、かつ文章が100文字以上のとき、

         テキストファイルの保存、または上書き
        */
        if( ($textbox_group==='text')&&( strlen($request->main_value)>99 ) )
        {
            # 基本設定
            $id = $textbox!==null? $textbox->id: Textbox::orderBy('id','desc')->first()->id +1;
            $dir = 'upload/text/';
            $file = sprintf('%06d',$id).'.txt';
            $text = $request->main_value;

            # ストレージにテキストファイルを保存
            Storage::put($dir.$file, $text);

            # $save_dataに値を保存
            $save_data['main_value'] = $dir.$file;
            $save_data['sub_value'] = 'strage_upload';

        }


        return $save_data;
    }




    /**
     * テキストファイルを削除(deleteTextFile)
     *
     * @param App\Models\Textbox $textbox (更新前のテキストボックスのデータ)
     */
    public static function deleteTextFile($textbox)
    {
        $textbox_group = TextboxCase::find($textbox->textbox_case_id)->group;

        if(
            ($textbox_group === 'text')&&($textbox->sub_value === 'strage_upload')
        ){
            Storage::delete($textbox->main_value); //削除
        }

    }

}
