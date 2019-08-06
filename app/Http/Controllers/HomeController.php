<?php

namespace App\Http\Controllers;

/**
 * The controller that displayes the first page.
 *
 * @author Björn Berggren
 */

class HomeController extends Controller
{

    /**
     * Create a new HomeController instance.
     *
     * @return HomeController
     */
    public function __construct()
    {}

    /**
     * Show the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMainPage()
    {
        return view('home');
    }
}
