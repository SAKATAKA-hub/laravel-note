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


        # 文章の取得(ストレージ保存のテキスト、又はテーブルデータ)
        $textbox_group = TextboxCase::find($this->textbox_case_id)->group;

        if( ($textbox_group === 'text')&&($this->sub_value) )
        {
            $value = Storage::get($this->main_value);
        }
        else
        {
            $value = $this->main_value;
        }


        # '改行'と'<strong>の入れ替え処理
        $value = e($value);
        $value = str_replace('{{','<strong>',$value);
        $value = str_replace('}}','</strong>',$value);
        $value = nl2br($value);

        return $value;
    }




    /**
     * $textbox->main_value_input
     * S3にテキストが保存されているときは、その内容を表示
     *
     *
     * @return String
     */
    public function getMainValueInputAttribute()
    {

        # 文章の取得(ストレージ保存のテキスト、又はテーブルデータ)
        $textbox_group = TextboxCase::find($this->textbox_case_id)->group;

        if( ($textbox_group === 'text')&&($this->sub_value) )
        {
            $value = Storage::get($this->main_value);
        }
        else
        {
            $value = $this->main_value;
        }

        return $value;
    }





    /**
     * $textbox->image_url
     * DBに保存された画像のURLを表示
     *
     *
     * @return String
     */
    public function getImageUrlAttribute()
    {
        return  $this->main_value;
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
