<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use App\Models\UserPartner;
use Illuminate\Http\Request;

class UserPartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataById = UserPartner::where('id', $id)->get();
        // test for retrieving only partners that are enabled
        // $dataById = UserPartner::where('id', $id)
        // ->whereHas('partner', function ($query) {
        //     $query->where('enabled', 1);
        // })
        // ->get();
        return $dataById;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getuserpartners($user_id)
    {
        $dataById = User::with('partners')->where('id', $user_id)
            ->whereHas('partners', function ($query) {
                $query->where('enabled', 1);
            })
            ->get();
        return $dataById;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|integer',
            'enabled' => 'required|integer'
        ]);
        // Get the authenticated user
        $user = auth()->user(); // or $user = Auth::user();

        $userpartner =  UserPartner::where('user_id', $user->id)
            ->where('partner_id', $request->partner_id)
            ->first();


        // if there is a result, update the enabled to 1
        if ($userpartner) {
            $userpartner->enabled = 1;
            $userpartner->update();
        }
        // if it doesn't exist, create it
        else {
            return $this->create($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        // check if the partner id exists
        $partner = Partner::find($request->partner_id);
        if ($partner) {

            $validated = $request->validate([
                'user_id' => 'required|integer',
                'partner_id' => 'required|integer',
                'enabled' => 'required|integer'
            ]);
            $userpartner = new UserPartner();
            $userpartner->user_id = $request->user_id;
            $userpartner->partner_id = $request->partner_id;
            $userpartner->enabled = $request->enabled;
            $userpartner->save();
        } else {
            return response()->json(['message' => 'this partner id doesn\'t exist'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
