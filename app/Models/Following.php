<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
    use HasFactory;

    protected $with = ['follower', 'following'];

    public function follower()
    {
        return $this->hasOne(User::class, 'id', 'follower_id');
    }

    public function following()
    {
        return $this->hasOne(User::class, 'id', 'followed_id');
    }
}
