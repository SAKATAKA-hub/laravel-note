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
        'title','color','tags','user_id','created_at','updated_at','publication_at',
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

    public function textboxes()
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
     * $note->time_text
     * ノートの公開日等を表示する
     *
     *
     * @return String
     */
    public function getTimeTextAttribute()
    {
        $text = '';

        # 作成日 (マイページ管理者がログイン中のみ表示)
        $created_at = \Carbon\Carbon::parse($this->created_at)->format('作成 : Y年m月d日 H:i').'　';
        if ( Auth::user() && ($this->user_id == Auth::id()) ){ $text .= $created_at; }

        # 公開日時(公開設定が公開中の時のみ表示)
        $publication_at = \Carbon\Carbon::parse($this->publication_at)->format('公開 : Y年m月d日 H:i').'　';
        if ( isset($this->publication_at) ){ $text .= $publication_at; }

        # 更新日時
        // マイページ管理者がログイン中かつ、作成日時＜更新日時　または、
        // 公開設定が公開中かつ、公開日時＜更新日時
        $updated_at = \Carbon\Carbon::parse($this->updated_at)->format('更新 : Y年m月d日 H:i').'　';
        if (
            ( Auth::user() && ( $this->user_id == Auth::id() ) && ($this->created_at < $this->updated_at) ) or
            ( isset($this->publication_at) && ($this->publication_at < $this->updated_at) )
        )
        { $text .= $updated_at; }


        return $text;
        // 'created_at','updated_at','publication_at',
    }





    /**
     * $note->tags_array
     * 複数タグの値を、'文字列'から'配列'に変換して返す。
     *
     *
     * @return String
     */
    public function getTagsArrayAttribute()
    {
        return explode(',', str_replace("'",'',$this->tags) );
    }




    /**
     * $note->chake_publishing
     * ノート公開設定(公開か非公開か)を返す。
     *
     * 公開：'publication_at'カラムの値が、現在の日時より前の時。
     * 非公開：'publication_at'カラムの値がnull、または現在の日時より後の時。
     *
     * @return String
     */
    public function getChakePublishingAttribute()
    {
        $publication_at = $this->publication_at;
        $noe_date_time = \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s');


        return isset($publication_at) && ($publication_at < $noe_date_time)? true: false;
    }




    /**
     * $note->release_datetime_value
     * ノートに将来公開予定日が登録されている状態のとき、
     * ノートの基本情報編集で、release_datetime入力に入るvalueの値を返す。
     * (side_container>edit>note_title.blade.phpで利用)
     *
     * 公開待ち：'publication_at'カラムの値が、現在の日時より後の時。
     * 公開待ちではない：上記以外
     *
     *
     * @return String
     */
    public function getReleaseDatetimeValueAttribute()
    {
        $publication_at = $this->publication_at;
        $noe_date_time = \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s');


        return isset($publication_at) && $publication_at > $noe_date_time?
        str_replace(' ','T', substr($publication_at, 0,16) ): '';
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
        # 現在日時
        $now_at = \Carbon\Carbon::parse('now')->format('Y-m-d H:i:s');

        # マイページの管理者がログインしているかどうか。
        if ( Auth::user() && ($mypage_master->id == Auth::id()) )
        {
            //非公開データを含む
            $query->where('user_id',$mypage_master->id)->orderBy('created_at','desc')->orderBy('id','desc') ;
        }
        else
        {
            //非公開データを含まない
            $query->where('user_id',$mypage_master->id)->where('publication_at','<',$now_at)
            ->orderBy('created_at','desc')->orderBy('id','desc') ;
        }


        return $query;

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
        # 投稿したノートの取得
        $notes = $this::mypageNotes($mypage_master)->orderBy('created_at','desc')->orderBy('id','desc')->get();

        # 投稿月の配列
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
