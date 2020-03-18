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



?>