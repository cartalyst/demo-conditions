<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard to the user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('demo/index');
    }
}
