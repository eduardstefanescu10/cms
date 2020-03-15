<?php


namespace App\Controllers;
use CMS\Http\Response;


class Controller
{
    /**
     * The Response class
     *
     * @var \CMS\Http\Response
     */
    public $response;

    /**
     * Set response content
     *
     * @param int $status
     * @param string|array $content
     */
    public function setContent($status = 200, $content = '')
    {
        // Get Response class
        $this->response = $GLOBALS['response'];

        // Return response
        $this->response->setStatus($status);
        $this->response->setContent($content);
    }
}


?>