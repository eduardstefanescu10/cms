<?php


namespace CMS\Routing;


class Route
{
    /**
     * The URI pattern the route responds to
     *
     * @var string
     */
    public $uri;

    /**
     * The HTTP verbs
     *
     * @var array
     */
    public $methods;

    /**
     * The route action array
     *
     * @var array
     */
    public $action;

    /**
     * Route constructor
     *
     * @param string|array $methods
     * @param string $uri
     * @param string|callable|null $action
     */
    public function __construct($methods, $uri, $action)
    {
        $this->methods = $this->validateVerbs($methods);
        $this->uri = cleanUrl($uri);
        $this->action = $action;
    }

    /**
     * Validate HTTP methods
     *
     * @param string|array $methods
     *
     * @return bool|array
     */
    public function validateVerbs($methods)
    {
        // Check if method exists in verbs array
        if (in_array($methods, Router::$verbs)) {
            return [$methods];
        } elseif ($methods == Router::$verbs) {
            return $methods;
        }

        return false;
    }

    /**
     * Get route uri
     *
     * @return string
     */
    public function getUri()
    {
        return ltrim($this->uri, '/');
    }
}


?>