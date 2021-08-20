<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotePartName extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_key',
        'part_name',
    ];

}
