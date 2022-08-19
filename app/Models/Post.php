<?php

namespace App\Models;

use App\Http\Controllers\Api\V1\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media_type()
    {
        return $this->belongsTo(MediaType::class);
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class, 'post_id');
    }

    public function videos()
    {
        return $this->hasMany(Gallery::class, 'post_id')->where('media_type', 'video');
    }

    public function comments()
    {
        $blocked = (new Controller)->is_blocked();
        return $this->hasMany(Comment::class, 'post_id')->whereNotIn('user_id', $blocked)->orderByDesc('id')->limit(1);
    }
}
