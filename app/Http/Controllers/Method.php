<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Textbox;
use App\Models\TextboxCase;

use Illuminate\Support\Facades\Storage;


class Method
{
    public static function test()
    {
        return 'method test';
    }

    public static function self()
    {
        return self::test();
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
            $dir = '/upload/image'; //アップロード先ディレクトリ名
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
        $textbox_group = TextboxCase::where('value',$request->textbox_case_name)->first()->group;

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
