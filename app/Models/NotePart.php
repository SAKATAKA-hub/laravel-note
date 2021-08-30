<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotePart extends Model
{
    use HasFactory;


    # データ挿入設定
    protected $fillable = [
        'note_id','note_part_name_id','text','url',
    ];

    public $timestamps = true;



    # リレーション設定
    public function note_part_name()
    {
        return $this->belongsTo(NotePartName::class);
    }

}
