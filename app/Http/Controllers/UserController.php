<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all users
        $users  = User::all();

        $response =   $users;
        // return response($response, 200);
        // $response = ['message' => 'user index'];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        if ($id) {
            $user = User::with('Partners')->findOrFail($id);
            $response = $user;
            return response($response, 200);
        } else {
            return response('error', 'user id missing');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //in apiAuthController
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    //   delete user with all its relationships
    public function delete($userId)
    {
        if (!$userId || $userId == null) {
            return 'no user id';
        }
        
    }
}
