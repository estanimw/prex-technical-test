<?php

namespace App\Http\Clients;

use Illuminate\Support\Facades\Http;

class GiphyHttpClient
{
    protected $apiKey;
    protected $baseURL;

    public function __construct()
    {
        $this->apiKey = env('GIPHY_API_KEY');
        $this->baseURL = 'http://api.giphy.com/v1/';
    }

    public function get($url, $queryParams)
    {
        $queryParams['api_key'] = $this->apiKey;
        return Http::get($this->baseURL . $url, $queryParams);
    }
}
