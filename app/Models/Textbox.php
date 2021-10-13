<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Textbox extends Model
{
    use HasFactory;

    # データ挿入設定
    public $timestamps = true;

    protected $fillable = [
        'note_id', 'textbox_case_id','main_value', 'sub_value', 'order',
    ];




    # アクセサー
    /**
     * 'main_value'カラムの表示に'改行'と'<strong>タグ'を反映させる
     * ($textbox->replace_main_value)
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
