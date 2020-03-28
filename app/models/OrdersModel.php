<?php


namespace App\Models;


class OrdersModel extends Model
{
    /**
     * Get orders from the database based on search criteria
     *
     * @param array $request
     *
     * @return array|null
     */
    public function list($request = array())
    {
        // Variables
        $result = null;
        $statusList = '';
        $offset = (($request['page'] * $request['number']) - $request['number']);
        $limit = $request['number'];

        // Loop status
        foreach ($request['status'] as $value) {
            $statusList .= $value . ',';
        }

        // Trim last comma
        $statusList = rtrim($statusList, ',');

        // Check if searchText is null
        if (!isset($request['searchText'])) {
            // Without search text
            $sql = "
                SELECT 
                    cms_orders.ID, cms_orders.email, cms_orders.countryCode, cms_orders.phone,
                    cms_orders.firstName, cms_orders.lastName, cms_orders.total, cms_orders.status,
                    cms_orders.added, cms_orders_shipping.country 
                FROM cms_orders
                INNER JOIN cms_orders_shipping 
                ON cms_orders.ID=cms_orders_shipping.orderID 
                WHERE cms_orders.status IN(:status) 
                AND (cms_orders.added BETWEEN :startDate AND :endDate) 
                ORDER BY cms_orders.added DESC      
            ";
            $result = $this->get(
                $sql,
                [
                    'status'    => $statusList,
                    'startDate' => $request['startDate'],
                    'endDate'   => $request['endDate']
                ],
                $limit,
                $offset
            );
        } else {
            // With search text
            // TO DO...
        }

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
}


?>