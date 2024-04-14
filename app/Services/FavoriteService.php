<?php

namespace App\Services;

use App\Models\Favorite;

class FavoriteService
{
    public function saveFavorite($userId, $gifId, $alias)
    {
        return Favorite::create([
            'user_id' => $userId,
            'gif_id' => $gifId,
            'alias' => $alias
        ]);
    }
}