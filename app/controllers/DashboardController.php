<?php


namespace App\Controllers;


class DashboardController extends Controller
{
    /**
     * Default action
     */
    public function index()
    {
        // Get view
        view('dashboard');
    }
}


?>