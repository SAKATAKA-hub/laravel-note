<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(3)->create();
        // $this->call(NotePartNamesTableSeeder::class);


        $this->call(ColorsTableSeeder::class);
        $this->call(TextboxCasesTableSeeder::class);

        $this->call(UsersTableSeeder::class);

        $this->call(NotesTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(TextboxsTableSeeder::class);
    }



    # 投稿のフェイクデータを挿入するユーザーのID
    public static function getUserId()
    {
        return 2;
        // return 15;
    }

    # テキストボックスを挿入する投稿ノートのID
    public static function getNoteId()
    {
        return 1;
        // return 5;
    }

    # herokuのclearDBのテーブルIDは、($n-1)*10+5で附番される
    public static function textboxCaseId($n)
    {
        return $n;
        // return ($n-1)*10+5;
    }

}
