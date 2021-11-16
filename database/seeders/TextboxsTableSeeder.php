<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Textbox;

class TextboxsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $note_id = DatabaseSeeder::getNoteId();

        $items =[
           '1' => [
               'main_value' => '{{Sample}}見出し1',
               'sub_value' => null,
            ],
           '2' => [
               'main_value' => '{{Sample}}見出し2',
               'sub_value' => null,
            ],
           '3' => [
               'main_value' => '{{Sample}}見出し3',
               'sub_value' => null,
            ],

           '4' => [
               'main_value' => '{{Sample}}通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 通常の文章 ',
               'sub_value' => null,
            ],
           '5' => [
               'main_value' => '{{Sample}}重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 重要な文章 ',
               'sub_value' => null,
            ],
           '6' => [
               'main_value' => '{{Sample}}強調する文章 強調する文章 強調する文章 強調する文章 強調する文章 強調する文章 強調する文章 強調する文章 強調する文章',
               'sub_value' => null,
            ],
           '7' => [
               'main_value' => '{{Sample}}引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 引用文 ',
               'sub_value' => null,
            ],
           '8' => [
               'main_value' => 'コード文',
               'sub_value' => 'code.php',
            ],
           '9' => [
               'main_value' => 'https://www.google.com/',
               'sub_value' => 'google',
            ],
           '10' => [
               'main_value' => 'Gc3CdUbviE5HtZI8Ypwf862Te5cXRNDh8VixH85T.jpg',
               'sub_value' => '大きい画像',
            ],
           '11' => [
                'main_value' => 'Gc3CdUbviE5HtZI8Ypwf862Te5cXRNDh8VixH85T.jpg',
                'sub_value' => '小さい画像',
            ],
        ];

        foreach ($items as $n => $item)
        {
            $data = new Textbox([

                'note_id' => $note_id,

                'textbox_case_id' => $n,

                'main_value' => $item['main_value'],

                'sub_value' => $item['sub_value'],

                'order' => $n,
            ]);
            $data->save();
        }






    }
}
