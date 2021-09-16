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
        \App\Models\User::factory(2)->create();
        $this->call(NotePartNamesTableSeeder::class);
        $this->call(TagsTableSeeder::class);

        $this->call(NotesTableSeeder::class);

    }
}
