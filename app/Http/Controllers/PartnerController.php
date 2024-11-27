<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use App\Models\UserPartner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partners = Partner::where('enabled', 1)->get();
        return $partners;
    }

    // get partners of users
    public function userpartners()
    {
        // $userpartners = Partner::with('users')->where('enabled', 1)->get();
        $userpartners = User::whereHas('partners', fn($query) => $query->where('enabled', 1))->with('partners')->get();
        // $userpartners = User::with('partners')->where('enabled', 1)->get();
        return $userpartners;
    }

    // get all partners that aren't related to user
    public function nonuserpartners()
    {
        $user = auth()->user(); // Current authenticated user
        $userId = $user->id;
    
        // Step 1: Get all partner IDs linked to the user with enabled = 1
        $linkedPartnerIds = UserPartner::where('user_id', $userId)
            ->where('enabled', 1)
            ->pluck('partner_id');
    
        // Step 2: Get all partners where `enabled != 0` and exclude linked partner IDs
        $notuserpartners = Partner::where('enabled', '!=', 0)
            ->whereNotIn('id', $linkedPartnerIds)
            ->get();
    
        return $notuserpartners;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner' => 'required|string|max:255',
        ]);

        $request->partners()->create($validated);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        //
    }
}
