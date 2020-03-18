<?php


namespace CMS\Http;


class Response
{
    /**
     * The headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * The content
     *
     * @var string|array
     */
    protected $content;

    /**
     * Status texts
     *
     * @var array
     */
    protected $statusTexts = [
        // Informal
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        // Client error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // Server error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * Set the response content
     *
     * @param string|array $content
     */
    public function setContent($content)
    {
        // Get content
        $this->content = $content;

        // Check if content is JSON
        if (is_array($this->content)) {
            $this->setHeader('Content-Type: application/json; charset=UTF-8');
        }
    }

    /**
     * Set status code
     *
     * @param int $code
     */
    public function setStatus(int $code)
    {
        $this->setHeader('HTTP/1.1 '. $code . ' ' . $this->getStatusCodeText($code));
    }

    /**
     * Get status code text
     *
     * @param int $code
     */
    public function getStatusCodeText(int $code)
    {
        return isset($this->statusTexts[$code]) ? $this->statusTexts[$code] : 'unknown status';
    }

    /**
     * Set header
     *
     * @param string $header
     */
    public function setHeader(string $header)
    {
        $this->headers[] = $header;
    }

    /**
     * Send the response
     */
    public function send()
    {
        // Check if headers are sent
        if (!headers_sent()) {
            // Loop headers
            foreach($this->headers as $header) {
                // Send header
                header($header, true);
            }

            // Check if content is not empty
            if (!empty($this->content)) {
                echo json_encode($this->content);
            }
        }
    }
}

?>