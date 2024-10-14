<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GmapsService
{
    protected $apiKey;
    protected $headers;

    public function __construct()
    {
        $this->apiKey = config('services.gmaps.api_key');
        $this->headers = [
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip,deflate,br',
            'Content-Type' => 'application/json',
            'X-Goog-Api-Key' => $this->apiKey,
            'X-Goog-FieldMask' => 'places.displayName,places.formattedAddress,places.id'
        ];
    }

    public function getNearbyPlacesOld($location, $radius, $paramType, $paramValue)
    {
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location={$location}&radius={$radius}&{$paramType}={$paramValue}&maxResultCount=2&key={$this->apiKey}";

        try {
            $response = Http::get($url);
            // Log::debug($response);
            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }
    public function getPlaceDistances($destinationPlaceId, $location)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations=place_id:{$destinationPlaceId}&origins={$location}&key={$this->apiKey}";
        try {
            $response = Http::get($url);
            // Log::debug($response);
            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }

    public function searchTextNewApi($latitude, $longitude, $textQuery, $maxResultCount)
    {
        $url = "https://places.googleapis.com/v1/places:searchText";
        $body = '{
                    "textQuery": "' . $textQuery . '",
                    "maxResultCount": ' . $maxResultCount . ',
                    "rankPreference": "DISTANCE",
                    "locationBias": {
                        "circle": {
                            "center": {
                                "latitude": ' . $latitude . ',
                                "longitude": ' . $longitude . ',
                            },
                            "radius": 2000.0
                        }
                    }
                }';
        try {
            $response = Http::withHeaders($this->headers)
                ->withBody($body, 'application/json')
                ->post($url);

            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }
}
