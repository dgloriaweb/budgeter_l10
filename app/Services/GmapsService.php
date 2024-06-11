<?php 

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GmapsService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gmaps.api_key');
    }

    public function getNearbyPlaces($location, $radius, $keyword)
    {
        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location={$location}&radius={$radius}&keyword={$keyword}&key={$this->apiKey}";

        try {
            $response = Http::get($url);
            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }
    public function getPlaceDistances($destinationPlaceId,$location)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations=place_id:{$destinationPlaceId}&origins={$location}&key={$this->apiKey}";
        try {
            $response = Http::get($url);
            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }
    
}

