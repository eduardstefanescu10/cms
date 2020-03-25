<?php


namespace CMS\Database;
use PDO;
use PDOException;


class Database
{
    /**
     * The database host
     *
     * @var string
     */
    private $host = 'localhost';

    /**
     * The database username
     *
     * @var string
     */
    private $user = 'root';

    /**
     * The database password
     *
     * @var string
     */
    private $pass = '';

    /**
     * The database name
     *
     * @var string
     */
    private $name = 'workplace_cms_db';

    /**
     * Database properties
     *
     * @var array
     */
    private $options = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    );

    /**
     * The database data source name
     *
     * @var null|string
     */
    private $dsn = null;

    /**
     * The database connection
     *
     * @var null|PDO
     */
    private $dbc = null;

    /**
     * The database error
     *
     * @var null|string
     */
    private $error = null;

    /**
     * Connect to database
     *
     * @return PDO|null
     */
    public function connect()
    {
        try {
            // Create new PDO connection
            $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
            $this->dbc = new PDO($this->dsn, $this->user, $this->pass, $this->options);

            return $this->dbc;
        } catch (PDOException $exception) {
            // Get error
            $this->error = $exception;

            // Save log
            saveLog('Database connection failed: ' . $this->error);

            return null;
        }
    }
}


?>