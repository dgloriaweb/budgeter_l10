<?php

namespace App\Http\Controllers;

use App\Models\PartnerIncomes;
use App\Http\Requests\StorePartnerIncomesRequest;
use App\Http\Requests\UpdatePartnerIncomesRequest;

class PartnerIncomesController extends Controller
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
     * @param  \App\Http\Requests\StorePartnerIncomesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartnerIncomesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerIncomes  $partnerIncomes
     * @return \Illuminate\Http\Response
     */
    public function show(PartnerIncomes $partnerIncomes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerIncomes  $partnerIncomes
     * @return \Illuminate\Http\Response
     */
    public function edit(PartnerIncomes $partnerIncomes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePartnerIncomesRequest  $request
     * @param  \App\Models\PartnerIncomes  $partnerIncomes
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePartnerIncomesRequest $request, PartnerIncomes $partnerIncomes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerIncomes  $partnerIncomes
     * @return \Illuminate\Http\Response
     */
    public function destroy(PartnerIncomes $partnerIncomes)
    {
        //
    }
}
