<?php


namespace App\Models;
use CMS\Database\Database;


class Model
{
    /**
     * The Database class
     *
     * @var \CMS\Database\Database
     */
    protected $db = null;

    /**
     * The database connection
     *
     * @var null|PDO
     */
    protected $dbc = null;

    /**
     * Model constructor
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Insert new row in the database
     *
     * @param string $table
     * @param array $params
     *
     * @return int
     */
    protected function create(string $table, $params = array())
    {
        // Connect to database
        $this->dbc = $this->db->connect();

        // Check connection
        if ($this->dbc == null) {
            return 0;
        }

        // Variables
        $columnsList = '';
        $valuesList  = '';

        // Loop params
        foreach($params as $key => $value) {
            // Get the key and value
            $columnsList .= $key .',';
            $valuesList  .= "'" . $value . "',";
        }

        // Remove last comma
        $columnsList = rtrim($columnsList, ',');
        $valuesList  = rtrim($valuesList, ',');

        // Prepare statement
        $stm = $this->dbc->prepare("INSERT INTO $table ($columnsList) VALUES($valuesList)");

        // Check result
        if (!$stm->execute()) {
            // Close connection
            $this->dbc = null;

            return 0;
        } else {
            // Get inserted ID
            $ID = $this->dbc->lastInsertId();

            // Close connection
            $this->dbc = null;

            return $ID;
        }
    }

    /**
     * Get values from the database
     *
     * @param string $sql
     * @param array $params
     * @param null $limit
     * @param null $offset
     *
     * @return null|array
     */
    protected function get(string $sql, $params = array(), $limit = null, $offset = null)
    {
        // Connect to database
        $this->dbc = $this->db->connect();

        // Check connection
        if ($this->dbc == null) {
            return null;
        }

        // Check if limit is not null
        if ($limit != null) {
            $sql = $sql . ' LIMIT ' . $limit;
        }

        // Check if offset is not null
        if ($offset != null) {
            $sql = $sql . ' OFFSET ' . $offset;
        }

        // Prepare statement
        $stm = $this->dbc->prepare($sql);

        // Check if there are params
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                $stm->bindValue("$key", $value);
            }
        }

        // Check result
        if (!$stm->execute()) {
            // Close connection
            $this->dbc = null;

            return null;
        } else {
            // Close connection
            $this->dbc = null;

            return $stm->fetchAll();
        }
    }

    /**
     * Update date in database
     *
     * @param string $table
     * @param array $params
     * @param array $where
     * @param string $options
     *
     * @return int
     */
    protected function update(string $table, $params = array(), $where = array(), string $options = '')
    {
        // Connect to database
        $this->dbc = $this->db->connect();

        // Check connection
        if ($this->dbc == null) {
            return 0;
        }

        // Variables
        $allArray     = null;
        $whereValues  = null;
        $paramsValue  = null;
        $updateValues = null;

        // Loop params
        foreach ($params as $key => $value) {
            $updateValues .= $key . '=:' . $key . ',';
        }

        // Loop where
        foreach ($where as $key2 => $value2) {
            $whereValues .= $key2 . '=:' . $key2 . ' AND ';
        }

        // Remove last comma
        $updateValues = rtrim($updateValues, ',');

        // Remove last AND
        $whereValuesLen = strlen($whereValues);
        $whereValues    = substr($whereValues, 0, $whereValuesLen - 4);

        // Prepare statement
        $stm = $this->dbc->prepare("UPDATE $table SET $updateValues WHERE $whereValues $options");

        // Merge arrays
        $allArray = array_merge($params, $where);

        // Check result
        if (!$stm->execute($allArray)) {
            // Close connection
            $this->dbc = null;

            return 0;
        } else {
            // Close connection
            $this->dbc = null;

            return $stm->rowCount();
        }
    }

    /**
     * Delete data from database
     *
     * @param string $table
     * @param string $where
     *
     * @return null|int $limit
     */
    protected function delete(string $table, $where, $limit = null)
    {
        // Connect to database
        $this->dbc = $this->db->connect();

        // Check connection
        if ($this->dbc == null) {
            return 0;
        }

        // Check limit
        if ($limit == null) {
            // Prepare statement
            $stm = $this->dbc->exec("DELETE FROM $table WHERE $where");
        } else {
            // Prepare statement
            $stm = $this->dbc->exec("DELETE FROM $table WHERE $where LIMIT $limit");
        }

        // Close connection
        $this->dbc = null;

        return $stm;
    }
}


?>