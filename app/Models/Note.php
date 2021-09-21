<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Note extends Model
{
    use HasFactory;

    # データ挿入設定
    public $timestamps = true;

    protected $fillable = [
        'title','color','tags','publishing','user_id',
    ];




    # リレーション設定

    /**
     * userテーブルとのリレーション
     * (user)
     *
     *
     */

    public function user()
    {
        return $this->belongsTo(user::class);
    }


    /**
     * textboxテーブルとのリレーション
     * (textboxs)
     *
     *
     */

    public function textboxs()
    {
        return $this->hasMany(Textbox::class)->orderBy('order','asc');
    }





    # アクセサー
    /**
     * 文字列で記録された複数タグの値を、配列に変換して返す。
     * ($note->tags_array)
     *
     *
     * @return String
     */
    public function getTagsArrayAttribute()
    {
        return explode(',', str_replace("'",'',$this->tags) );
    }





    # ローカルスコープ

    /**
     * 指定された複数のタグを含むノートのデータを取得
     * ( Note::seacrhTags($tags,$mypage_master) )
     *
     *
     * @return Array
     */
    public function scopeSeacrhTags($query,$tags,$mypage_master)
    {
        $tags = str_replace('tags=[','', str_replace(']','',$tags) );
        $tags = explode(',',$tags);

        $tag=$tags[0];


        // マイページ管理者のデータのみ取得
        $query->where(function($query) use($mypage_master){
            $query->where('user_id',$mypage_master->id);
        });

        // 指定された複数のタグを含むノートのデータを取得
        foreach ($tags as $tag)
        {
            $query->where(function($query) use($tag){
                $query->where('tags','like',"%".$tag."%");
            });
        }




        return $query;
    }


    # 同じタグが利用されている投稿数を返す
    public function scopeCountTagUsed($query,$user_id,$tag)
    {
        return $query->where('user_id',$user_id)
        ->where('tags','like','%#'.$tag.',%')->count();
    }


}
