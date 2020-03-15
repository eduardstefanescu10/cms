<?php


namespace App\Controllers;


class NotFoundController extends Controller
{
    public function index()
    {
        $this->setContent(404);
    }
}