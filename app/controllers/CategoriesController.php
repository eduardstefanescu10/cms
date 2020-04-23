<?php


namespace App\Controllers;
use App\Models\CategoriesModel;
use CMS\Auth\Auth;


class CategoriesController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\CategoriesModel
     */
    private $model;

    /**
     * OrdersController constructor
     */
    public function __construct()
    {
        $this->model = new CategoriesModel();
    }

    /**
     * Default action
     */
    public function index()
    {
        // Check if logged
        if (Auth::checkLogin()) {
            // Get view
            view('categories');
        } else {
            // Redirect to logout page
            redirect('logout');
        }
    }

    public function newCategory()
    {
        // Check if logged
        if (Auth::checkLogin()) {
            // Get view
            view('categories.new');
        } else {
            // Redirect to logout page
            redirect('logout');
        }
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
                // Check if empty
                if (empty($json['searchText'])) {
                    $json['searchText'] = null;
                } else {
                    // Add to values array
                    $values['searchText'] = filter_var(
                        $json['searchText'],
                        FILTER_SANITIZE_STRING,
                        array(
                            'flags' => FILTER_FLAG_STRIP_LOW | FILTER_FLAG_ENCODE_HIGH
                        )
                    );
                }
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
                    $statusList = '';

                    // Loop array
                    foreach ($json['status'] as $status) {
                        // Check value
                        switch ($status)
                        {
                            case 'DRAFT':
                                $statusList .= '0,';
                                break;
                            case 'AVAILABLE':
                                $statusList .= '1,';
                                break;
                            case 'TRASH':
                                $statusList .= '2,';
                                break;
                            default:
                                $this->setContent(400);
                                return;
                        }
                    }

                    // Trim last comma
                    $statusList = rtrim($statusList, ',');

                    // Add to values array
                    $values['status'] = $statusList;
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
            // Add to values array
            $values['startDate'] = null;
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
            // Add to values array
            $values['endDate'] = null;
        }

        $result = $this->model->list($values);

        // Check result
        if ($result !== null) {
            // OK
            $this->setContent(200, array('categories' => $result));
            return;
        } else {
            // Internal Server Error
            $this->setContent(500);
            return;
        }
    }

    /**
     * Create new category
     */
    public function create()
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

        // Check if name is set
        if (isset($json['name'])) {
            // Trim
            $name = trim($json['name'], ' ');

            // Check max length
            if (strlen($name) < 51) {
                // Check minim length
                if (strlen($name) > 0) {
                    // Check if clean string
                    if (ctype_alnum(str_replace(' ', '', $name))) {
                        // Add value to array
                        $values['name'] = $name;
                    } else {
                        $this->setContent(200, array('status' => 'name_chars'));
                        return;
                    }
                } else {
                    $this->setContent(200, array('status' => 'name_length_min'));
                    return;
                }
            } else {
                $this->setContent(200, array('status' => 'name_length_max'));
                return;
            }
        } else {
            $this->setContent(200, array('status' => 'name_missing'));
            return;
        }

        // Check if status is set
        if (isset($json['status'])) {
            // Check if status is valid
            if ($json['status'] == '0' || $json['status'] == '1' || $json['status'] == '2') {
                // Add value to array
                $values['status'] = $json['status'];
            } else {
                $this->setContent(200, array('status' => 'status_invalid'));
                return;
            }
        } else {
            $this->setContent(200, array('status' => 'status_missing'));
            return;
        }

        // Create category
        $result = $this->model->insert($values['name'], $values['status']);

        // Check result
        if ($result) {
            // OK
            $this->setContent(200, array('status' => 'success'));
            return;
        } else {
            // Internal Server Error
            $this->setContent(500);
            return;
        }
    }
}