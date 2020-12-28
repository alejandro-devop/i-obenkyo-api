<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HabitCategory;
use App\Models\User;

class HabitCategorySeeder extends Seeder
{
    private $categories = [
        ['name' => 'Health', 'icon' => 'heartbeat'],
        ['name' => 'Mind', 'icon' => 'brain'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        HabitCategory::truncate();
        $user = User::find(1)?: new User;
        foreach ($this->categories as $categoryData) {
            $user->habitCategories()->save(new HabitCategory($categoryData));
        }
    }
}
