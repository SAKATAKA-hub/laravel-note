<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;


    # データ挿入設定
    protected $fillable = [
        'title','main_image','main_color','tags','user_id',
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
}
