<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\TextboxCase;



class Textbox extends Model
{
    use HasFactory;

    # データ挿入設定
    public $timestamps = true;

    protected $fillable = [
        'note_id', 'textbox_case_id','main_value', 'sub_value', 'order',
    ];




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * $textbox->replace_main_value
     * 'main_value'カラムの表示に'改行'と'<strong>タグ'を反映させる
     *
     *
     * @return String
     */
    public function getReplaceMainValueAttribute()
    {
        $value = e($this->main_value);
        $value = str_replace('{{','<strong>',$value);
        $value = str_replace('}}','</strong>',$value);
        $value = nl2br($value);

        return $value;
    }




    /**
     * $textbox->image_url
     * S3に保存された画像のURLを表示
     *
     *
     * @return String
     */
    public function getImageUrlAttribute()
    {
        //-----------------------------------------------------
        // 開発環境時には、local stragの'sample.jpg'を表示
        // デプロイ後は、S3に保存した画像を表示
        //-----------------------------------------------------

        $local_path = 'img/sample.jpg';
        $s3_path = $this->main_value;
        $url = '';

        // 開発環境のとき、
        if( Storage::disk('local')->exists($local_path) )
        {
            $url = 'http://localhost/laravel-note/public/'.Storage::disk('local')->url($local_path);
        }
        // デプロイ後で、S3に保存データがあるとき、
        elseif( Storage::disk('s3')->exists($s3_path) )
        {
            Storage::disk('s3')->url($s3_path);
        }

        return $url;
    }




    /*
    |--------------------------------------------------------------------------
    | リレーション設定
    |--------------------------------------------------------------------------
    |
    |
    */


    /**
     * TextboxCaseテーブルとのリレーション
     * ($textbox->textboxCase)
     *
     *
     */

    public function textboxCase()
    {
        return $this->belongsTo(TextboxCase::class);
    }




    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    |
    |
    */
    /**
     * changeOrders($request, $note)
     * マイページ管理者のノートを取得
     * (マイページの管理者がログインしていなければ、非公開ノートの非表示)
     *
     *
     * @return Array
    */
    public function scopeChangeOrders($query, $request, $note)
    {
        return $query->where('note_id',$note->id)
        ->where('order','>=',$request->order)
        ->orderBy('order','asc')->get();

    }




}
