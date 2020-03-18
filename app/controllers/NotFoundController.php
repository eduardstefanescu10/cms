<?php


namespace App\Controllers;


class NotFoundController extends Controller
{
    /**
     * Default action
     */
    public function index()
    {
        // Get view
        view('404');
    }
}