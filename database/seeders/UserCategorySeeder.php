<?php

namespace Database\Seeders;

use App\Models\UserCategory;
use Illuminate\Database\Seeder;

class UserCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCategory::create(['name_az' => 'Influencer', 'name_en' => 'Influencer', 'name_ru' => 'Инфлюенсер']);
        UserCategory::create(['name_az' => 'Developer', 'name_en' => 'Developer', 'name_ru' => 'Developer']);
        UserCategory::create(['name_az' => 'Photographer', 'name_en' => 'Photographer', 'name_ru' => 'Photographer']);
    }
}
