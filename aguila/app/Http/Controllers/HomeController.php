<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function sales(){
        return view('home.sales',[
            'date_start' => date('Y-m-d 00:00:00'),
            'date_end'   => date('Y-m-d 23:59:59')
        ]);

    }
}
