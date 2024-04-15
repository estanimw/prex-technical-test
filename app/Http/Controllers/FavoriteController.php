<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Favorite;
use App\Services\FavoriteService;
use App\Http\Requests\SaveFavoriteRequest;

class FavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function store(SaveFavoriteRequest $request)
    {
        $user_id = $request->user_id;
        $gif_id = $request->gif_id;
        $alias = $request->alias;

        $this->favoriteService->saveFavorite($user_id, $gif_id, $alias);
        return response()->json([], Response::HTTP_CREATED);
    }
}
