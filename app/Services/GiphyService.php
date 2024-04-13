<?php

namespace App\Services;

use App\Http\Clients\GiphyHttpClient;

class GiphyService
{
    protected $httpClient;

    public function __construct(GiphyHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function searchGifs($searchTerm, $offset = 0, $limit = 25)
    {
        $response = $this->httpClient->get('gifs/search', [
            'q' => $searchTerm,
            'offset' => $offset,
            'limit' => $limit
        ]);

        return $response->json();
    }

    public function getGifById($gif_id)
    {
        $response = $this->httpClient->get('gifs/' . $gif_id, []);

        return $response->json();
    }
}
