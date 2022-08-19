<?php

namespace Database\Seeders;

use App\Models\NotifType;
use Illuminate\Database\Seeder;

class NotifTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NotifType::create(['name' => 'follow']);
        NotifType::create(['name' => 'followRequest']);
        NotifType::create(['name' => 'comment']);
        NotifType::create(['name' => 'like']);
        NotifType::create(['name' => 'commentLike']);
        NotifType::create(['name' => 'commentReply']);
        NotifType::create(['name' => 'mention']);
    }
}
