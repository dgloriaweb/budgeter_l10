<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $textQueryArray = ["public toilet", "medical centre"];
        // Get the authenticated user
        $user = auth()->user(); // or $user = Auth::user();

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $combinedPlaces = [];
        $location = $latitude . ',' . $longitude;
        $type = $request->input('type');
        $places = [];
        // if ($type == "loo") {
        // we need to do separate requests for different types.
        foreach ($textQueryArray as $textQuery) {
            $maxResultCount = 2;
            // here we will have to do many requests, and merge them together
            $resultset = $this->gmapsService->searchTextNewApi($latitude, $longitude, $textQuery, $maxResultCount);
            foreach ($resultset['places'] as $resultitem){
                array_push($places, $resultitem);
            }
        }
        // dd($places);
        // Add distance data to each place
        foreach ($places as &$place) {
            $distanceData = $this->gmapsService->getPlaceDistances($place['id'], $location);
            $place['distance'] = $distanceData['rows'][0]['elements'][0]['distance']['text'] ?? null;
        }

        // } else {
        //     $type = "delivery"; // change this to the below request to find all that has food delivery
        //     // https://maps.googleapis.com/maps/api/place/details/json?key=<key>&id=ChIJ3Q1tAkdVdkgRnKZ4Td8bVFk&fields=name,opening_hours,delivery,takeout


        //     $places = $this->gmapsService->getNearbyPlacesOld($location, 2000, "keyword", $type);

        //     // Add distance data to each place
        //     foreach ($places['places']  as &$place) {
        //         $distanceData = $this->gmapsService->getPlaceDistances($place['id'], $location);
        //         $place['distance'] = $distanceData['rows'][0]['elements'][0]['distance']['text'] ?? null;
        //     }

        usort($places, function ($a, $b) {
            // Convert distance strings to meters for comparison
            $distanceA = $this->convertDistanceToMeters($a['distance']);
            $distanceB = $this->convertDistanceToMeters($b['distance']);

            return $distanceA <=> $distanceB;
        });
        // // Merge the results into the combined array
        // $combinedPlaces = array_merge($combinedPlaces, $places['results']);
        // }
        // // Remove duplicates based on coordinates
        // $filteredPlaces = $this->removeDuplicates($combinedPlaces);

        // Output the URL
        $uniquePlaces = $this->removeDuplicates($places);


        if ($user instanceof User) {
            // increase the counter for the user
            // move this higher up, don't do the request if the value is already 5
            $counter =  $user->patreon_daily_counter;
            $counter++;
            $user->update([
                'patreon_daily_counter' => $counter,
            ]);

            // Return the filtered places
            // Return the updated user data
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'userCounter' => $counter, // Return the updated user
                'results' => $uniquePlaces
            ]);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    private function removeDuplicates($placesArray)
    {
        $uniquePlaces = [];

        foreach ($placesArray as $place) {
            $coordinates = $place['location'];

            // Check if a place with similar coordinates already exists
            $existingPlace = array_values(array_filter($uniquePlaces, function ($p) use ($coordinates) {
                return $this->areCoordinatesSimilar($p['location'], $coordinates);
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
        $lat1 = round($coord1['latitude'], $decimalPrecision);
        $lng1 = round($coord1['longitude'], $decimalPrecision);

        $lat2 = round($coord2['latitude'], $decimalPrecision);
        $lng2 = round($coord2['longitude'], $decimalPrecision);

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
    // public function getNearbyPlacesControl(Request $request)
    // {
    //     $latitude = $request->input('latitude');
    //     $longitude = $request->input('longitude');

    //     $places = $this->gmapsService->mergeAndOrderResults($latitude, $longitude);

    //     // Return the filtered places
    //     return response()->json(['results' => $places]);
    // }

}
