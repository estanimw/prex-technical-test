<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\SearchGifsRequest;
use App\Services\GiphyService;
use App\Http\Requests\GetGifByIdRequest;

class GiphyController extends Controller
{
    protected $giphyService;

    public function __construct(GiphyService $giphyService)
    {
        $this->giphyService = $giphyService;
    }

    public function search(SearchGifsRequest $request)
    {
        $searchTerm = $request->input('query');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 25);

        return $this->giphyService->searchGifs($searchTerm, $offset, $limit);
    }

    public function getById(GetGifByIdRequest $request)
    {
        $gif_id = $request->id;
        return $this->giphyService->getGifById($gif_id);
    }
}
