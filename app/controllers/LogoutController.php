<?php


namespace App\Controllers;


class LogoutController extends Controller
{
    /**
     * The default action
     */
    public function index()
    {
        // Check for sessions
        if (isset($_SESSION['ID']) || isset($_SESSION['username']) || isset($_SESSION['password']) || isset($_SESSION['hash'])) {
            // Remove sessions
            clearSession('ID');
            clearSession('username');
            clearSession('password');
            clearSession('hash');
        }

        // Check for cookies
        if (isset($_COOKIE['ID']) || isset($_COOKIE['username']) || isset($_COOKIE['password']) || isset($_COOKIE['hash'])) {
            // Remove cookies
            clearCookie('ID');
            clearCookie('username');
            clearCookie('password');
            clearCookie('hash');
        }

        // Redirect to log in page
        redirect('login');
    }
}

?>