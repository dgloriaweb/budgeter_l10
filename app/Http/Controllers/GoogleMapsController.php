<?php

namespace App\Http\Controllers;

use App\Services\GmapsService;
use Illuminate\Http\Request;

class GoogleMapsController extends Controller
{
    protected $gmapsService;

    public function __construct(GmapsService $gmapsService)
    {
        $this->gmapsService = $gmapsService;
    }

    public function getNearbyPlacesControl(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 2500);
        $location = $latitude . ',' . $longitude;
        $combinedPlaces = [];
        $type = $request->input('type');

        if ($type == "loo") {
            // todo, enable user to add types
            $keywords = ["mcdonalds", "asda", "sainsburys", "costa"];

            foreach ($keywords as $keyword) {
                $places = $this->gmapsService->getNearbyPlaces($location, $radius, "keyword", $keyword);

                // Add distance data to each place
                foreach ($places['results'] as &$place) {
                    $distanceData = $this->gmapsService->getPlaceDistances($place['place_id'], $location);
                    $place['distance'] = $distanceData['rows'][0]['elements'][0]['distance']['text'] ?? null;
                }

                // Merge the results into the combined array
                $combinedPlaces = array_merge($combinedPlaces, $places['results']);
            }
        } else {
            $type = "delivery"; // change this to the below request to find all that has food delivery
            // https://maps.googleapis.com/maps/api/place/details/json?key=<key>&place_id=ChIJ3Q1tAkdVdkgRnKZ4Td8bVFk&fields=name,opening_hours,delivery,takeout


            $places = $this->gmapsService->getNearbyPlaces($location, $radius, "keyword", $type);

            // Add distance data to each place
            foreach ($places['results'] as &$place) {
                $distanceData = $this->gmapsService->getPlaceDistances($place['place_id'], $location);
                $place['distance'] = $distanceData['rows'][0]['elements'][0]['distance']['text'] ?? null;
            }

            // Merge the results into the combined array
            $combinedPlaces = array_merge($combinedPlaces, $places['results']);
        }
        // Remove duplicates based on coordinates
        $filteredPlaces = $this->removeDuplicates($combinedPlaces);

        // Sort the array by distance
        usort($filteredPlaces, function ($a, $b) {
            // Convert distance strings to meters for comparison
            $distanceA = $this->convertDistanceToMeters($a['distance']);
            $distanceB = $this->convertDistanceToMeters($b['distance']);

            return $distanceA <=> $distanceB;
        });


        // increase the counter for the user
        $user = $request->user();
        $counter =  $user->patreon_deaily_counter;
        $counter++;
        $user->update([
            'counter' => $counter,
        ]);

        // Return the filtered places
        return response()->json(['results' => $filteredPlaces]);
    }

    private function removeDuplicates($placesArray)
    {
        $uniquePlaces = [];

        foreach ($placesArray as $place) {
            $coordinates = $place['geometry']['location'];

            // Check if a place with similar coordinates already exists
            $existingPlace = array_values(array_filter($uniquePlaces, function ($p) use ($coordinates) {
                return $this->areCoordinatesSimilar($p['geometry']['location'], $coordinates);
            }));

            // If no matching place is found, add the current place to the unique list
            if (empty($existingPlace)) {
                $uniquePlaces[] = $place;
            }
        }

        return $uniquePlaces;
    }

    private function areCoordinatesSimilar($coord1, $coord2, $decimalPrecision = 3)
    {
        $lat1 = round($coord1['lat'], $decimalPrecision);
        $lng1 = round($coord1['lng'], $decimalPrecision);

        $lat2 = round($coord2['lat'], $decimalPrecision);
        $lng2 = round($coord2['lng'], $decimalPrecision);

        return ($lat1 == $lat2) && ($lng1 == $lng2);
    }
    private  function convertDistanceToMeters($distance)
    {
        // Assume distance is in the format "X.XX km" or "X.XX m"
        $parts = explode(' ', $distance);

        if (count($parts) === 2) {
            $value = floatval($parts[0]);
            $unit = strtolower($parts[1]);

            if ($unit === 'km') {
                return $value * 1000;
            } elseif ($unit === 'm') {
                return $value;
            }
        }

        return 0; // Default to 0 if distance format is unexpected
    }
}
