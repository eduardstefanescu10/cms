<?php


namespace CMS\Auth;
use App\Models\AccountModel;


class Auth
{
    /**
     * The admin ID
     *
     * @var int
     */
    public static $ID;

    /**
     * The admin username
     *
     * @var string
     */
    public static $username;

    /**
     * The admin password
     *
     * @var string
     */
    public static $password;

    /**
     * The current session hash
     *
     * @var string
     */
    public static $sessionHash;

    /**
     * The current log in status
     *
     * @var bool
     */
    public static $isLogged = false;

    /**
     * Check log in session
     *
     * @return bool
     */
    public static function checkLogin()
    {
        // Check if session exists
        if (self::checkSession()) {
            // Get values
            $session = array(
                'ID'          => self::$ID,
                'username'    => self::$username,
                'password'    => self::$password,
                'sessionHash' => self::$sessionHash
            );

            // Create new AccountModel object
            $accountModel = new AccountModel();

            // Check if session in database
            if ($accountModel->validateSession($session)) {
                // Valid
                self::$isLogged = true;

                return true;
            } else {
                // Not valid
                return false;
            }
        } else {
            // Doesn't exists
            return false;
        }
    }

    /**
     * Check current session status
     *
     * @return bool
     */
    public static function checkSession()
    {
        // Check if session exists
        if (isset($_SESSION['ID']) && isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['hash'])) {
            // Get session values
            self::$ID          = getSession('ID');
            self::$username    = getSession('username');
            self::$password    = getSession('password');
            self::$sessionHash = getSession('hash');

            // Sanitize values
            self::$ID          = (int) self::$ID;
            self::$username    = sanitizeUsername(self::$username);
            self::$password    = sanitizeString(self::$password);
            self::$sessionHash = sanitizeString(self::$sessionHash);

            return true;
        } elseif (isset($_COOKIE['ID']) && isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_COOKIE['hash'])) {
            // Get cookies values
            self::$ID          = getCookie('ID');
            self::$username    = getCookie('username');
            self::$password    = getCookie('password');
            self::$sessionHash = getCookie('hash');

            // Sanitize values
            self::$ID          = (int) self::$ID;
            self::$username    = sanitizeUsername(self::$username);
            self::$password    = sanitizeString(self::$password);
            self::$sessionHash = sanitizeString(self::$sessionHash);

            return true;
        }

        return false;
    }
}

?>