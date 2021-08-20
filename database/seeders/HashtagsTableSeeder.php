<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hashtag;

class HashtagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hashtag_vals=['アプリ開発','学習ノート','制作メモ','Laravel'];

        foreach ($hashtag_vals as $hashtag_val) {
            $Hashtag = new Hashtag([
                'hashtag' => $hashtag_val
            ]);
            $Hashtag->save();
        }

    }
}
