<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

use App\Http\ViewComposers\S3ImageUrlComposer;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','image','comment','password','app_dministrator','easy_user',
    ];




    /*
    |--------------------------------------------------------------------------
    | アクセサー
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * $user->open_post
     * ユーザーの公開中投稿数を表示
     *
     *
     * @return String
     */
    public function getOpenPostAttribute()
    {
        return Note::publicationOrderMypageNotes($this)->count();
    }




    /**
     * $user->private_post
     * ユーザーの非公開投稿数を表示
     *
     *
     * @return String
     */
    public function getPrivatePostAttribute()
    {
        return Note::unpublishedOrderMypageNotes($this)->count();
    }



    /**
     * $user->replace_comment
     * 'comment'カラムの表示に'改行'を反映させる
     *
     *
     * @return String
     */
    public function getReplaceCommentAttribute()
    {
        $value = e($this->comment);
        $value = nl2br($value);

        return $value;
    }

    /**
     * $user->image_url
     * S3に保存されたユーザー画像のURLを表示
     * 画像の登録がない場合は、'no-image'の画像を表示(パスはS3ImageUrlComposerより参照)
     *
     *
     * @return String
     */
    public function getImageUrlAttribute()
    {

        $local_path = 'img/user0001.png';// ローカル環境画像
        $s3_path = $this->image; //ユーザー登録画像
        $no_image = 'people/mMU8CNbJtMfnjF5hjmgooUaZ1r4kwpyvQYWPmCmR.png'; //ユーザー画像の登録なしのパス
        $url = '';

        // 開発環境のとき、
        if( Storage::disk('local')->exists($local_path) )
        {
            $url = 'http://localhost/laravel-note/public/'.Storage::disk('local')->url($local_path);
        }

        // テーブルに保存された画像のパスがNULL
        elseif(empty($s3_path))
        {
            $url = Storage::disk('s3')->url($no_image);
        }

        // S3に保存データがあるとき、
        elseif (Storage::disk('s3')->exists($s3_path))
        {
            $url = Storage::disk('s3')->url($s3_path);
        }

        return $url;







        // // テーブルに保存された画像のパスを参照。NULLなら'no-image'画像のパスを参照。
        // $path = empty($this->image)? S3ImageUrlComposer::filePath()['no_image']: $this->image;

        // // S3に画像が保存されていれば表示、なければ非表示
        // return Storage::disk('s3')->exists($path)? Storage::disk('s3')->url($path):'';
    }

}
