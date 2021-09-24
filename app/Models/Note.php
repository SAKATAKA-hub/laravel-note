<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Note extends Model
{
    /*
    |--------------------------------------------------------------------------
    | データ挿入設定
    |--------------------------------------------------------------------------
    |
    |
    */

    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'title','color','tags','publishing','user_id',
    ];








    /*
    |--------------------------------------------------------------------------
    | リレーション設定
    |--------------------------------------------------------------------------
    |
    |
    */


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









    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */


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








    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * mypageNotes($mypage_master)
     * マイページ管理者のノートを取得
     * (マイページの管理者がログインしていなければ、非公開ノートの非表示)
     *
     *
     * @return Array
    */
    public function scopeMypageNotes($query, $mypage_master)
    {
        return ( Auth::user() && ($mypage_master->id == Auth::id()) ) ? //マイページの管理者がログインしているかどうか。
           $query->where('user_id',$mypage_master->id)->orderBy('id','desc') : //非公開データを含む
           $query->where('user_id',$mypage_master->id)->where('publishing',1)
           ->orderBy('id','desc') //非公開データを含まない
        ;
    }




    /**
     * TagsListCount($mypage_master,$tag)
     * 同じタグの総投稿数を返す
     * tagモデル\scopeTagsListメソッド内で利用する。
     *
     * @return Array
     */
    public function scopeTagsListCount($query,$mypage_master,$tag)
    {
        $count = $this::mypageNotes($mypage_master)
        ->where('tags', 'like', '%'.$tag.'%')
        ->count();

        return $count;
    }





    /**
     * monthsListCount($mypage_master,$month_value)
     * 各年月の総投稿数を返す
     * scopeMonthsListメソッド内で利用する。
     *
     *
     * @return Array
     */
    public function scopeMonthsListCount($query,$mypage_master,$month)
    {
        $count =  $this::mypageNotes($mypage_master)
        ->where('created_at','like', $month.'%')
        ->count();

        return $count;
    }


    /**
     * monthsList($mypage_master)
     * 年月の選択リストを返す。
     *
     *
     * @return Array
     */
    public function scopemonthsList($query,$mypage_master)
    {
        // 投稿したノートの取得
        $notes = $this::mypageNotes($mypage_master)->orderBy('id','desc')->get();

        // 投稿月の配列
        $months = [];
        foreach ($notes as $note)
        {
            $months[] = substr($note->created_at,0,7); //0000-00
        }
        $months = array_unique($months); //重複の削除


        $months_list = [];
        foreach ($months as $month_value)
        {
            $months_list[] = [
                'name' => 'month',
                'value' => $month_value, //0000-00
                'text' => str_replace('-','年',$month_value).'月', //0000年00月
                'checked' => 0,
                'count' => $this::monthsListCount($mypage_master,$month_value), //同じ月の投稿数
            ];
        }

        return $months_list;
    }

}
