<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $note = new Note([
            'title' => 'title 1',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート','制作メモ'",
            'publishing' => 1,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 2',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'publishing' => 1,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 3',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'publishing' => 1,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 4',
            'color' => 'green',
            'tags' => "'Laravel'",
            'publishing' => 1,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 5',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート','制作メモ'",
            'publishing' => 0,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 6',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'publishing' => 0,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 7',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'publishing' => 0,
            'user_id' => 1,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 8',
            'color' => 'green',
            'tags' => "'Laravel'",
            'publishing' => 0,
            'user_id' => 1,
        ]);
        $note->save();








        $note = new Note([
            'title' => 'title 9',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート','制作メモ'",
            'publishing' => 1,
            'user_id' => 2,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 10',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'publishing' => 1,
            'user_id' => 2,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 11',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'publishing' => 0,
            'user_id' => 2,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 12',
            'color' => 'green',
            'tags' => "'Laravel'",
            'publishing' => 0,
            'user_id' => 2,
        ]);
        $note->save();
    }
}
