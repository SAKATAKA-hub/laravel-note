<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','image','comment','password','locked_flg','error_count',
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



}
