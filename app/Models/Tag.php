<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    # データ挿入設定
    public $timestamps = true;

    protected $fillable = [
        'name','value','text','user_id','checked',
    ];

}