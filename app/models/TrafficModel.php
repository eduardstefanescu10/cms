<?php


namespace App\Models;


class TrafficModel extends Model
{
    /**
     * Get the number of views for a specific date
     *
     * @param string $date
     *
     * @return int
     */
    public function getDayViews(string $date)
    {
        $sql = "SELECT ID FROM cms_traffic WHERE DATE(added)=:added";
        $result = $this->get(
            $sql,
            [
                'added' => $date
            ]
        );

        // Check result
        if ($result !== null) {
            // Return results
            return count($result);
        } else {
            // Failed
            // Save log
            saveLog('TrafficModel failed on method getDayViews for the sql: ' . $sql);
            return 0;
        }
    }
}


?>