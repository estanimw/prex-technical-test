<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveFavoriteRequest;
use App\Services\FavoriteService;
use Illuminate\Http\Response;

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
