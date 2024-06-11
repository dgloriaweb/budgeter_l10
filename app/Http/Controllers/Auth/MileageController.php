<?php

namespace App\Http\Controllers;

use App\Models\Mileage;
use Exception;
use Illuminate\Http\Request;

class MileageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mileages = Mileage::with('partner', 'locationstart', 'locationend')->get();
        return $mileages;
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $lastMileageData = Mileage::latest()->first();
        $mileageDataById = Mileage::where('id', $id)->get();
        return $mileageDataById;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function report($id)
    {
        if ($id) {
            $mileages_by_user = Mileage::with('partner', 'locationstart', 'locationend')->where('user_id', $id)->get();
            return $mileages_by_user;
        } else {
            return response('error', 'user_id id missing');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'opening_mileage' => 'required|integer',
            'closing_mileage' => 'required|integer',
            'partner_id' => 'required|integer',
            'location_id_start' => 'required|integer',
            'location_id_end' => 'required|integer',
            'personal_travel_at_start' => 'integer',
            'personal_travel_at_start' => 'integer',
            'comments' => 'string|max:255',
        ]);

        $request->user()->mileages()->create($validated);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mileage  $mileage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {

        $validated = $request->validate([
            'date' => 'required|date',
            'opening_mileage' => 'required|integer',
            'closing_mileage' => 'required|integer',
            'partner_id' => 'required|integer',
            'location_id_start' => 'required|integer',
            'location_id_end' => 'required|integer',
            'personal_travel_at_start' => 'integer',
            'personal_travel_at_start' => 'integer',
            'comments' => 'nullable|string|max:255',
        ]);

        $mileage = Mileage::find($id);
        $mileage->date = $request->date;
        $mileage->opening_mileage = $request->opening_mileage;
        $mileage->closing_mileage = $request->closing_mileage;
        $mileage->partner_id = $request->partner_id;
        $mileage->location_id_start = $request->location_id_start;
        $mileage->location_id_end = $request->location_id_end;
        $mileage->personal_travel_at_start = $request->personal_travel_at_start;
        $mileage->personal_travel_at_end = $request->personal_travel_at_end;
        $mileage->comments = $request->comments;

        $mileage->save();
    }

    /**
     * Find the last row entry
     */
    public function lastMileageData($id)
    {
        // $lastMileageData = Mileage::latest()->first();
        $lastMileageData = Mileage::where('user_id', $id)
            ->orderby('closing_mileage', 'desc')->first();
        return $lastMileageData;
    }
}
