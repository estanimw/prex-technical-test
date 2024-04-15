<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteGif extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'gif_id', 'alias'];

    // Define the relationship between FavoriteGif and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
