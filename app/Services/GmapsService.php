<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GmapsService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gmaps.api_key');
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
    public function getNearbyPlaces($latitude, $longitude,  $radius, array $includedTypes)
    {
        $url = "https://places.googleapis.com/v1/places:searchNearby";
        $body = [
            "locationRestriction" => [
                "circle" => [
                    "center" => [
                        "latitude" => $latitude,
                        "longitude" => $longitude
                    ],
                    "radius" => $radius
                ]
            ],
            "rankPreference" => "DISTANCE", //sort the results
            "includedTypes" => $includedTypes,
        ];
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $this->apiKey,
                'X-Goog-FieldMask' => 'places.displayName,places.formattedAddress,places.delivery,places.id'
            ])->post($url, array_merge($body));

            return $response->json();
        } catch (\Exception $e) {
            // Handle errors, log, etc.
            return ['error' => 'Internal Server Error'];
        }
    }
}
