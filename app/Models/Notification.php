<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function action_user()
    {
        $this->hasOne(User::class, 'id', 'action_id');
    }

    public function post()
    {
        $this->hasOne(Post::class, 'id', 'post_id');
    }
}
