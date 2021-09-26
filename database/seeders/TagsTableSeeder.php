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
        $user_id = 1;
        $tag_vals=['Laravel','アプリ開発','学習ノート','制作メモ'];

        foreach ($tag_vals as $tag_val) {
            $tag = new Tag([
                'value' => "'".$tag_val."'", //" 'tag_val' "
                'text' => $tag_val, //" tag_val "
                'user_id' => $user_id,
            ]);
            $tag->save();
        }

    }
}
