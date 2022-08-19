<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserCategorySeeder::class);
        $this->call(PostSeeder::class);
        $this->call(GallerySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(StorySeeder::class);
        $this->call(HobbySeeder::class);

    }
}
