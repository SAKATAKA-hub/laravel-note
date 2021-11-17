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
        'name','email','image','comment','password','app_dministrator','locked_flg','error_count',
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
     * $user->image_url
     * S3に保存されたユーザー画像のURLを表示
     * 画像の登録がない場合は、'no-image'の画像を表示(パスはS3ImageUrlComposerより参照)
     *
     *
     * @return String
     */
    public function getImageUrlAttribute()
    {
        // *S3利用節約中
        return '';

        // テーブルに保存された画像のパスを参照。NULLなら'no-image'画像のパスを参照。
        $path = empty($this->image)? S3ImageUrlComposer::filePath()['no_image']: $this->image;

        // S3に画像が保存されていれば表示、なければ非表示
        return Storage::disk('s3')->exists($path)? Storage::disk('s3')->url($path):'';
    }

}
