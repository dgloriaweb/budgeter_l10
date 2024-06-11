<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $userId;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(Request $request)
    {
        return 'hello world';
    }

    public function demo()
    {
        $data = [
            'title' => 'Demo'
        ];
        return view('demo',  $data);
    }


}
