<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetGifByIdRequest;
use App\Http\Requests\SearchGifsRequest;
use App\Services\GiphyService;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Http;

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

        $data = $this->giphyService->searchGifs($searchTerm, $offset, $limit);
        $responseHttpCode = $data['meta']['status'];

        return response()->json(['gifs' => $data['data']], $responseHttpCode);
    }

    public function getById(GetGifByIdRequest $request)
    {
        $gif_id = $request->id;

        $data = $this->giphyService->getGifById($gif_id);
        $responseHttpCode = $data['meta']['status'];

        return response()->json(['gif' => $data['data']], $responseHttpCode);
    }
}
