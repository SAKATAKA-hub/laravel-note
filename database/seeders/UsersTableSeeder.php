<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'sakataka',
                'email' => 'sakataka@mail.co.jp',
                'image' => 'people/XEAn7eZNutmZwS6Vujaccl5ZIHr4GO4RkItWZ7xz.png',
            ],[
                'name' => '佐々木 直子',
                'email' => 'sasaki@mail.co.jp',
                'image' => 'people/ZDb0IafPzLMq1vmeyAC41VX0heXJOSKqxOeazDbZ.png',
            ],[
                'name' => '杉山 聡太郎',
                'email' => 'sugiyama@mail.co.jp',
                'image' => 'people/xvy4286IS55i6pnNWtpaCAx9KroaPstamZhIDiaN.png',
            ],

        ];

        foreach ($items as $i => $item) {
            $user = new User([
                'name' => $item['name'],
                'email' => $item['email'],
                'password' => Hash::make('password'),
                'image' => $item['image'],
                'comment' => 'ヨロシクオネガイシマス。',
                'app_dministrator' => $i == 0? 1: 0,
            ]);
            $user->save();
        }

    }
}
