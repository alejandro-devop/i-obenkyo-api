<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanSlateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('habits')->truncate();
        DB::table('habit_categories')->truncate();
        DB::table('tasks')->truncate();
        DB::table('task_groups')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
