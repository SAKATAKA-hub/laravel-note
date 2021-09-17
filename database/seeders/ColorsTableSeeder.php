<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'color';
        $items =[
            ['value' => 'green', 'text' => 'グリーン'],
            ['value' => 'teal', 'text' => 'ティール'],
            ['value' => 'cyan', 'text' => 'シアン'],
            ['value' => 'blue', 'text' => 'ブルー'],
            ['value' => 'indigo', 'text' => 'インディゴ'],
            ['value' => 'purple', 'text' => 'パープル'],
            ['value' => 'pink', 'text' => 'ピンク'],
            ['value' => 'red', 'text' => 'レッド'],
            ['value' => 'orange', 'text' => 'オレンジ'],
            ['value' => 'yellow', 'text' => 'イエロー'],
        ];

        foreach ($items as $item)
        {
            $data = new Color([
                'name' => $name,
                'value' => $item['value'],
                'text' => $item['text'],
            ]);
            $data->save();
        }
    }
}

