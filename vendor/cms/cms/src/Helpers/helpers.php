<?php


if (!function_exists('redirect')) {
    /**
     * Redirect to the given url
     *
     * @param string $where
     */
    function redirect($where = '') {
        // Check if $where has value
        if ($where != '') {
            $where = '/' . $where;
        }

        // Check if localhost
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            // Redirect to localhost
            header('location: http://localhost' . OPTIONS['SITE_SUB_FOLDER'] . $where);
            exit();
        } else {
            // Check if HTTPS is set
            if (isset($_SERVER['HTTPS'])) {
                // Check if HTTPS is on
                if ($_SERVER['HTTPS'] == 'on') {
                    // HTTPS redirect
                    header('location: https://www.' . $_SERVER['HTTP_HOST'] . OPTIONS['SITE_SUB_FOLDER'] . $where);
                    exit();
                } else {
                    // HTTP redirect
                    header('location: http://www.' . $_SERVER['HTTP_HOST'] . OPTIONS['SITE_SUB_FOLDER'] . $where);
                    exit();
                }
            } else {
                // HTTP redirect
                header('location: http://www.' . $_SERVER['HTTP_HOST'] . OPTIONS['SITE_SUB_FOLDER'] . $where);
                exit();
            }
        }
    }
}


if (!function_exists('cleanUrl')) {
    /**
     * Clean url
     *
     * @param string $url
     * @return string
     */
    function cleanUrl($url) {
        return str_replace(['%20', ' '], '-', $url);
    }
}


if (!function_exists('view')) {
    /**
     * Get view
     *
     * @param string $path
     * @param array $params
     */
    function view(string $path, $params = []) {
        // Define full view path
        $fullPath = VIEWS . $path . '.phtml';

        // Check if view exists
        if (file_exists($fullPath)) {
            include_once $fullPath;
        } else {
            echo '<b>' . $path . '</b> not found';
        }
    }
}


if (!function_exists('cryptPass')) {
    /**
     * Crypt password
     *
     * @param string $password
     *
     * @return string
     */
    function cryptPass(string $password) {
        // Hash
        $password = md5($password);

        // Salts
        $sal1 = '30EHehwr9349Bb4gu39gu3u9g39gj3g9GRWGWGw49';
        $salt2 = 'DFvsijfbSEg2397427t79vdGWRHWHWRh4ug38g38';

        // Cut password
        $part1 = substr($password, 0, 10);
        $part2 = substr($password, 10, 12);
        $part3 = substr($password, 22, 10);

        // Mix password
        $mixed = $part2 . $sal1 . $part3 . $salt2 . $part1;

        // Crypt password
        $mixed = sha1($mixed);

        // Return final password
        return md5($mixed);
    }
}


if (!function_exists('createSessionHash')) {
    /**
     * Create session hash
     *
     * @return string
     */
    function createSessionHash() {
        // Generate random number
        $randomNumber = rand(99999, 99999999);

        // Shuffle string
        $string = str_shuffle("ABCDEFGHIJKLMNOPRSTQVWXYZabcdefghijklmnoprstqvwxyz1234567890") . $randomNumber;

        return sha1($string);
    }
}


if (!function_exists('setSession')) {
    /**
     * Set session
     *
     * @param string $key
     * @param string $value
     */
    function setSession(string $key, string $value) {
        $_SESSION[$key] = base64_encode($value);
    }
}


if (!function_exists('getSession')) {
    /**
     * Get session
     *
     * @param string $key
     *
     * @return mixed
     */
    function getSession(string $key) {
        return base64_decode($_SESSION[$key]);
    }
}


if (!function_exists('clearSession')) {
    /**
     * Clear session
     *
     * @param string $key
     */
    function clearSession(string $key) {
        unset($_SESSION[$key]);
    }
}


if (!function_exists('setCook')) {
    /**
     * Set cookie
     *
     * @param string $key
     * @param mixed $value
     * @param int $days
     */
    function setCook(string $key, $value, int $days) {
        setcookie($key, base64_encode($value), time()+($days * 86400), '/', '', false, true);
    }
}


if (!function_exists('getCookie')) {
    /**
     * Get cookie
     *
     * @param string $key
     *
     * @return mixed
     */
    function getCookie(string $key) {
        return base64_decode($_COOKIE[$key]);
    }
}


if (!function_exists('clearCookie')) {
    /**
     * Clear cookie
     *
     * @param string $key
     */
    function clearCookie(string $key) {
        setcookie($key, null, time()-86400, '/', '');
    }
}



if (!function_exists('sanitizeUsername')) {
    /**
     * Sanitize username
     *
     * @param string $username
     *
     * @return string
     */
    function sanitizeUsername($username) {
        // Check if empty
        if (empty($username)) {
            return $username;
        }

        // Check if longer than 20 chars
        if (strlen($username) > 20) {
            return '';
        }

        // Return only string
        return preg_replace('/[^a-zA-Z0-9]/', '', $username);
    }
}


if (!function_exists('sanitizeString')) {
    function sanitizeString($string) {
        // Check string length
        if (strlen($string) > 256) {
            // Take only first 255 chars
            $string = substr($string, 0, 255);
        }

        // Return only string
        return preg_replace('/[^a-zA-Z0-9]/', '', $string);
    }
}


?>