<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;


    # データ挿入設定
    protected $fillable = [
        'title','color','tags','publishing','user_id',
    ];

    public $timestamps = true;




    # リレーション設定
    public function user()
    {
        return $this->belongsTo(user::class);
    }


    public function note_parts()
    {
        return $this->hasMany(note_part::class);
    }





    # アクセサー
    /**
     * フォーム入力値とラベルを結びつける'ID'の値を返す
     *
     *
     * @return String
     */
    public function getTagsArrayAttribute()
    {

        return explode('　',$this->tags);
    }





    # ローカルスコープ

    # 同じタグが利用されている投稿数を返す
    public function scopeCountTagUsed($query,$user_id,$tag)
    {
        return $query->where('user_id',$user_id)
        ->where('tags','like','%#'.$tag.',%')->count();
    }


}
