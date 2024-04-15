<?php

namespace App\Services;

use App\Models\FavoriteGif;

class FavoriteService
{
    public function saveFavorite($userId, $gifId, $alias)
    {
        return FavoriteGif::updateOrCreate(
            [
                'user_id' => $userId,
                'gif_id' => $gifId
            ],
            [
                'alias' => $alias
            ]
        );
    }
}
