<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MeteoApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getMeteoData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.open-meteo.com/v1/forecast?latitude=48.85&longitude=2.35&current_weather=true'
        );

        return $response->toArray();
    }
}