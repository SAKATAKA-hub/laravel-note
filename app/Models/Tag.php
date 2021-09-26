<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
        'value','text','user_id',
    ];





    /*
    |--------------------------------------------------------------------------
    | ローカルスコープ
    |--------------------------------------------------------------------------
    |
    |

    */
    /**
     * tagsList($mypage_master)
     * タグの選択リストを返す。
     *
     *
     * @return Array
     */
    public function scopetagsList($query,$mypage_master)
    {
        $tags_list = $query->where('user_id',$mypage_master->id)->get();

        // タグに関する投稿数を追加(Note::TagsListCountメソッド)
        $n = count($tags_list);
        for ($i=0; $i < $n; $i++)
        {
            $tags_list[$i]->count = Note::TagsListCount($mypage_master,$tags_list[$i]->value);
        }

        return $tags_list;
    }

}
