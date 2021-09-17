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
            ['value' => "heading1", 'text' => '見出し1'],
            ['value' => "heading2", 'text' => '見出し2'],
            ['value' => "heading3", 'text' => '見出し3'],

            ['value' => "normalText", 'text' => '通常の文章'],
            ['value' => "importantText", 'text' => '重要な文章'],
            ['value' => "emphasizedText", 'text' => '強調する文章'],
            ['value' => "quoteText", 'text' => '引用文'],
            ['value' => "codeText", 'text' => 'コード文'],

            ['value' => "link", 'text' => 'リンク'],
            ['value' => "image", 'text' => '大きい画像'],
            ['value' => "image_litle", 'text' => '小さい画像'],
        ];



        foreach ($items as $item)
        {
            $data = new TextboxCase([
                'name' => $name,
                'value' => $item['value'],
                'text' => $item['text'],
            ]);
            $data->save();
        }
    }
}
