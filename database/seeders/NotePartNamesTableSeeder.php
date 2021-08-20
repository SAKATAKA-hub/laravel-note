<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotePartName;

class NotePartNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $note_parts=[
            'hedding1' => '見出し1',
            'hedding2' => '見出し2',
            'hedding3' => '見出し3',

            'text' => 'テキスト',
            'important_text' => '重要文',
            'quote_text' => '引用文',
            'code_text' => 'コード記述',
            'link' => 'リンク',

            'image' => '大きい画像',
            'small_image' => '小さい画像',
        ];

        foreach ($note_parts as $part_key => $part_name) {
            $NotePartName = new NotePartName( compact('part_key' ,'part_name') );
            $NotePartName->save();
        }

    }
}
