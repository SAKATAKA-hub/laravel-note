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
            'user_id' => 1,

            'created_at' => '2021-10-02 00:00:00',
            'updated_at' => '2021-10-02 00:00:00',
            'publication_at' => '2021-10-02 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 2',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'user_id' => 1,

            'created_at' => '2021-10-01 00:00:00',
            'updated_at' => '2021-10-01 00:00:00',
            'publication_at' => '2021-10-01 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 3',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'user_id' => 1,

            'created_at' => '2021-09-02 00:00:00',
            'updated_at' => '2021-09-02 00:00:00',
            'publication_at' => '2021-09-02 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 4',
            'color' => 'green',
            'tags' => "'Laravel'",
            'user_id' => 1,

            'created_at' => '2021-09-01 00:00:00',
            'updated_at' => '2021-09-01 00:00:00',
            'publication_at' => '2021-09-01 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 5',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート','制作メモ'",
            'user_id' => 1,

            'created_at' => '2021-08-02 00:00:00',
            'updated_at' => '2021-08-02 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 6',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'user_id' => 1,

            'created_at' => '2021-08-01 00:00:00',
            'updated_at' => '2021-08-01 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 7',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'user_id' => 1,

            'created_at' => '2021-07-02 00:00:00',
            'updated_at' => '2021-07-02 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 8',
            'color' => 'green',
            'tags' => "'Laravel'",
            'user_id' => 1,

            'created_at' => '2021-07-01 00:00:00',
            'updated_at' => '2021-07-01 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();








        $note = new Note([
            'title' => 'title 9',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート','制作メモ'",
            'user_id' => 2,

            'created_at' => '2021-10-02 00:00:00',
            'updated_at' => '2021-10-02 00:00:00',
            'publication_at' => '2021-10-02 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 10',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発','学習ノート'",
            'user_id' => 2,

            'created_at' => '2021-10-01 00:00:00',
            'updated_at' => '2021-10-01 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 11',
            'color' => 'green',
            'tags' => "'Laravel','アプリ開発'",
            'user_id' => 2,

            'created_at' => '2021-09-02 00:00:00',
            'updated_at' => '2021-09-02 00:00:00',
            'publication_at' => '2021-09-02 00:00:00',
        ]);
        $note->save();

        $note = new Note([
            'title' => 'title 12',
            'color' => 'green',
            'tags' => "'Laravel'",
            'user_id' => 2,

            'created_at' => '2021-09-01 00:00:00',
            'updated_at' => '2021-09-01 00:00:00',
            'publication_at' => null,
        ]);
        $note->save();
    }
}
