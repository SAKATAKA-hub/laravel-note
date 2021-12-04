<?php

namespace App\Http\ViewComposers;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class S3ImageUrlComposer
{

    /**
     * viewに変数を追加
     *
     *
     * @return \Illuminate\View\View
     */
    public function compose(View $view)
    {

        #AWS S3内のファイルパスを取得
        $s3_image_path = $this::filePath();


        #AWS S3に保存したファイルURL
        $image_url = [];
        foreach ($s3_image_path as $key => $path)
        {
            $image_url[$key] = '';

            // $image_url[$key] = Storage::disk('s3')->exists($path)?
            // Storage::disk('s3')->url($path): '';
        }

        # viewに変数を追加
        $view->with(['image_url' => $image_url]);

    }




    /**
     * AWS S3に保存したファイルの、S3内のファイルパス
     *
     *
     * @return Array
     */
    public static function filePath()
    {
        return[
            // Appロゴ
            'rogo' => 'common/YJolOkQzz7wGR8f1UZXAOQ11IgLONwHoTIcWthSy.png',
            // ゲストユーザー
            'gest_user' => 'people/mMU8CNbJtMfnjF5hjmgooUaZ1r4kwpyvQYWPmCmR.png',
            // 画像なし
            'no_image' => 'people/bPB1YDah6eoZMPTL5tKXX1XnW8P3rxEbOifqxMaM.png',
        ];
    }

}
