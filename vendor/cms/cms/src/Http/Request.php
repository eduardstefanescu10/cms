<?php


namespace CMS\Http;


class Request
{
    /**
     * Get COOKIE super global
     *
     * @var array
     */
    public $cookie;

    /**
     * Get REQUEST super global
     *
     * @var array
     */
    public $request;

    /**
     * Get FILES super global
     *
     * @var array
     */
    public $files;

    /**
     * Request constructor
     */
    public function __construct()
    {
        $this->request = $this->clean($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_COOKIE);
    }

    /**
     * Get value for server
     *
     * @param string $key
     *
     * @return array|string
     */
    public function server($key = '')
    {
        return isset($_SERVER[strtoupper($key)]) ? $this->clean($_SERVER[strtoupper($key)]) : $this->clean($_SERVER);
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->server('REQUEST_METHOD'));
    }

    /**
     * Returns the client IP address
     *
     * @return string
     */
    public function getClientIp()
    {
        return $this->server('REMOTE_ADDR');
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return str_replace('url=', '', $this->server('QUERY_STRING'));
    }

    /**
     * Clean data
     *
     * @param array|string $data
     *
     * @return array|string
     */
    public function clean($data)
    {
        // Check if $data is array
        if (is_array($data)) {
            // Loop array
            foreach($data as $key => $value) {
                // Delete $key
                unset($data[$key]);

                // Set new clean key
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            // Convert HTML tags to special chars
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
}


?>