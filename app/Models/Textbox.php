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
        return $this->main_value;




        $path = $this->main_value;
        return Storage::disk('s3')->exists($path)? Storage::disk('s3')->url($path): '';

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
     * apiData($note)
     * 一つのnoteに関連するtextboxの非同期通信用のデータを取得
     *
     *
     * @return Array
    */
    public function scopeApiData($query, $note)
    {
        // textboxデータの取得
        $textboxes = $query->where('note_id',$note->id)->orderBy('order','asc')->get();

        //　パラメーターの追加
        for ($i=0; $i < count($textboxes); $i++)
        {
            // textboxのデータ
            $textbox = $textboxes[$i];

            // 追加データ
            $add_items = TextboxCase::find( $textbox->textbox_case_id );

            // 追加データの挿入
            $textbox->replace_main_value = $this::getReplaceMainValueAttribute();
            $textbox->image_url = $this::getImageUrlAttribute();
            $textbox->group = $add_items->group;
            $textbox->case_name = $add_items->value;
            $textbox->mode ='selectTextbox';


        }

        return $textboxes;
    }





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
