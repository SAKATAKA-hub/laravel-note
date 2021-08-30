<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_vals=['アプリ開発','学習ノート','制作メモ','Laravel'];

        foreach ($tag_vals as $tag_val) {
            $tag = new Tag([
                'tag' => $tag_val,
                'user_id' => 1,
            ]);
            $tag->save();
        }

    }
}
