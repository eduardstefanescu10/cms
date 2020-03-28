<?php


namespace App\Controllers;
use App\Models\OrdersModel;
use CMS\Auth\Auth;


class OrdersController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\OrdersModel
     */
    private $model;

    /**
     * OrdersController constructor
     */
    public function __construct()
    {
        $this->model = new OrdersModel();
    }

    /**
     * Get orders from the database based on search criteria
     */
    public function list()
    {
        // Array to store values
        $values = [];

        // Check if logged
        if (!Auth::checkLogin()) {
            // Unauthorized
            $this->setContent(401);
            return;
        }

        // Check if POST request
        if (!isset($_POST)) {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Get json
        $json = json_decode(file_get_contents("php://input"), true);

        // Check if JSON is empty
        if (empty($json)) {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check if searchText is set
        if (isset($json['searchText'])) {
            // Check if length is less than 61 chars
            if (strlen($json['searchText']) < 61) {
                // Add to values array
                $values['searchText'] = filter_var(
                    $json['searchText'],
                    FILTER_SANITIZE_SPECIAL_CHARS,
                    array(
                        'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH
                    )
                );
            } else {
                // Bad request
                $this->setContent(400);
                return;
            }
        } else {
            // No search text
            $json['searchText'] = null;
        }

        // Check if number is set
        if (isset($json['number'])) {
            // Check if number is int
            if (is_int($json['number'])) {
                // Check that number is less than 51
                if ($json['number'] < 51) {
                    // Add to values array
                    $values['number'] = $json['number'];
                } else {
                    // Bad request
                    $this->setContent(400);
                    return;
                }
            } else {
                // Bad request
                $this->setContent(400);
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check if page is set
        if (isset($json['page'])) {
            // Check if number is int
            if (is_int($json['page'])) {
                // Check if page is less than 1000
                if ($json['page'] < 1000) {
                    // Add to values array
                    $values['page'] = $json['page'];
                } else {
                    // Bad request
                    $this->setContent(400);
                    return;
                }
            } else {
                // Bad request
                $this->setContent(400);
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check if status is set
        if (isset($json['status'])) {
            // Check if status is array
            if (is_array($json['status'])) {
                // Check if has at least one item and max 3
                if (count($json['status']) > 0 && count($json['status']) < 4) {
                    // Loop array
                    foreach ($json['status'] as $status) {
                        // Check if not valid status
                        if ($status != 'PENDING' && $status != 'COMPLETED' && $status != 'REJECTED') {
                            // Bad request
                            $this->setContent(400);
                            return;
                        }
                    }

                    // Add to values array
                    $values['status'] = $json['status'];
                } else {
                    // Bad request
                    $this->setContent(400);
                    return;
                }
            } else {
                // Bad request
                $this->setContent(400);
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check startDate
        if (isset($json['startDate'])) {
            // Check if date
            if (validateDate($json['startDate'])) {
                // Add to values array
                $values['startDate'] = $json['startDate'];
            } else {
                $this->setContent(400);
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check endDate
        if (isset($json['endDate'])) {
            // Check if date
            if (validateDate($json['endDate'])) {
                // Add to values array
                $values['endDate'] = $json['endDate'];
            } else {
                $this->setContent(400);
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        $result = $this->model->list($values);

        // Check result
        if ($result !== null) {
            // OK
            $this->setContent(200, array('orders' => $result));
            return;
        } else {
            // Internal Server Error
            $this->setContent(500);
            return;
        }
    }
}