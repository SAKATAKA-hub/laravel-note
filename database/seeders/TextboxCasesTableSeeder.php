<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TextboxCase;

class TextboxCasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = 'textbox_case';
        $items =[
            ['group' => 'heading', 'value' => "heading1", 'text' => '見出し1'],
            ['group' => 'heading', 'value' => "heading2", 'text' => '見出し2'],
            ['group' => 'heading', 'value' => "heading3", 'text' => '見出し3'],

            ['group' => 'text', 'value' => "normal_text", 'text' => '通常の文章'],
            ['group' => 'text', 'value' => "important_text", 'text' => '重要な文章'],
            ['group' => 'text', 'value' => "emphasized_text", 'text' => '強調する文章'],
            ['group' => 'text', 'value' => "quote_text", 'text' => '引用文'],
            ['group' => 'text', 'value' => "code_text", 'text' => 'コード文'],

            ['group' => 'link', 'value' => "link", 'text' => 'リンク'],

            ['group' => 'image', 'value' => "image", 'text' => '大きい画像'],
            ['group' => 'image', 'value' => "image_litle", 'text' => '小さい画像'],
        ];



        foreach ($items as $item)
        {
            $data = new TextboxCase([
                'group' => $item['group'],
                'name' => $name,
                'value' => $item['value'],
                'text' => $item['text'],
            ]);
            $data->save();
        }
    }
}
