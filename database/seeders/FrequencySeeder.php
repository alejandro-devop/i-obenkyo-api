<?php

namespace Database\Seeders;

use App\Models\FrequencyType;
use App\Models\User;
use Illuminate\Database\Seeder;

class FrequencySeeder extends Seeder
{
    private $data = [
        ['name' => 'Daily', 'days' => 1, 'is_daily' => true, 'is_weekly' => false, 'is_monthly' => false, 'is_every_year' => false],
        ['name' => 'Weekly', 'days' => 7, 'is_daily' => false, 'is_weekly' => true, 'is_monthly' => false, 'is_every_year' => false],
        ['name' => 'Monthly', 'days' => null, 'is_daily' => false, 'is_weekly' => false, 'is_monthly' => true, 'is_every_year' => false],
        ['name' => 'Every year', 'days' => null, 'is_daily' => false, 'is_weekly' => false, 'is_monthly' => false, 'is_every_year' => true],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1)?: new User;
        foreach ($this->data as $data) {
            $user->frequencies()->save(new FrequencyType($data));
        }
    }
}
