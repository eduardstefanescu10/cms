<?php


namespace App\Controllers;
use CMS\Auth\Auth;


class DashboardController extends Controller
{
    /**
     * Default action
     */
    public function index()
    {
        // Check if logged
        if (Auth::checkLogin()) {
            // Get view
            view('dashboard');
        } else {
            // Redirect to logout page
            redirect('logout');
        }
    }
}


?>