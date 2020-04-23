<?php


namespace App\Models;


class CategoriesModel extends Model
{
    /**
     * Get categories from the database based on search criteria
     *
     * @param array $request
     *
     * @return array|null
     */
    public function list($request = array())
    {
        // Variables
        $dateSearch = '';
        $searchArray = [];
        $result = null;
        $offset = (($request['page'] * $request['number']) - $request['number']);
        $limit = $request['number'];

        // Check if draft
        if (strpos($request['status'], '0') !== false) {
            $searchArray['draft'] = '0';
        } else {
            $searchArray['draft'] = '';
        }

        // Check if draft
        if (strpos($request['status'], '1') !== false) {
            $searchArray['available'] = '1';
        } else {
            $searchArray['available'] = '';
        }

        // Check if draft
        if (strpos($request['status'], '2') !== false) {
            $searchArray['trash'] = '2';
        } else {
            $searchArray['trash'] = '';
        }

        // Check if dates are set
        if (isset($request['startDate']) != null && isset($request['endDate']) != null) {
            // Search by date
            $dateSearch = 'AND (added BETWEEN :startDate AND :endDate) ';

            // Add dates to search array
            $searchArray['startDate'] = $request['startDate'];
            $searchArray['endDate'] = $request['endDate'];
        }

        // Check if searchText is null
        if (!isset($request['searchText'])) {
            // Without search text
            $sql = "
                SELECT * 
                FROM cms_products_categories 
                WHERE status IN(:draft, :available, :trash)
                $dateSearch
                ORDER BY added DESC 
            ";
        } else {
            // Add search text to search array
            $searchArray['searchText'] = $request['searchText'];

            // With search string
            $sql = "
                SELECT * 
                FROM cms_products_categories
                WHERE status IN(:draft, :available, :trash) 
                AND title LIKE CONCAT('%', :searchText, '%')
                $dateSearch 
                ORDER BY added DESC
            ";
        }

        // Get categories
        $result = $this->get(
            $sql,
            $searchArray,
            $limit,
            $offset
        );

        // Check result
        if ($result !== null) {
            // Check query count
            if (count($result) > 0) {
                // Return orders
                return $result;
            } else {
                // No orders found
                return array();
            }
        } else {
            // Failed
            return null;
        }
    }

    /**
     * Create new category
     *
     * @param string $name
     * @param string $status
     *
     * @return bool
     */
    public function insert(string $name, string $status)
    {
        $result = $this->create(
            'cms_products_categories',
            [
                'title' => $name,
                'status' => $status,
                'added' => date('Y-m-d H:s')
            ]
        );

        // Check result
        if ($result > 0) {
            // Success
            return true;
        } else {
            // Fail
            return false;
        }
    }
}