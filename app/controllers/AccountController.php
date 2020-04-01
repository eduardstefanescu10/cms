<?php


namespace App\Controllers;
use App\Models\AccountModel;
use CMS\Auth\Auth;


class AccountController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\AccountModel
     */
    private $model;

    /**
     * TrafficController constructor
     */
    public function __construct()
    {
        $this->model = new AccountModel();
    }

    /**
     * Default method
     */
    public function index()
    {
        // Check if logged
        if (Auth::checkLogin()) {
            // Get view
            view('account');
        } else {
            // Redirect to logout page
            redirect('logout');
        }
    }

    /**
     * Get admin's details
     */
    public function getDetails()
    {
        // Check if logged
        if (!Auth::checkLogin()) {
            // Unauthorized
            $this->setContent(401);
            return;
        }

        // Get admin's details
        $result = $this->model->getDetails(Auth::$ID);

        // Check result
        if ($result != null) {
            // OK
            $this->setContent(200, $result);
        } else {
            // Internal Server Error
            $this->setContent(500);
        }
    }

    /**
     * Update admin's details
     */
    public function updateDetails()
    {
        // Variable
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

        // Check if firstName is set
        if (isset($json['firstName'])) {
            // Trim
            $firstName = trim($json['firstName'], ' ');

            // Check length
            if (strlen($firstName) > 0 && strlen($firstName) < 51) {
                // Validate as string
                if (ctype_alpha(str_replace(' ', '', $firstName))) {
                    // Add value to list
                    $values['firstName'] = $firstName;
                } else {
                    // Invalid
                    $this->setContent(200, array('status' => 'first_name_invalid'));
                    return;
                }
            } else {
                // Length
                $this->setContent(200, array('status' => 'first_name_length'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400, array('status' => 'first_name_empty'));
            return;
        }

        // Check if lastName is set
        if (isset($json['lastName'])) {
            // Trim
            $lastName = trim($json['lastName'], ' ');

            // Check length
            if (strlen($lastName) > 0 && strlen($lastName) < 51) {
                // Validate as string
                if (ctype_alpha(str_replace(' ', '', $lastName))) {
                    // Add value to list
                    $values['lastName'] = $lastName;
                } else {
                    // Invalid
                    $this->setContent(200, array('status' => 'last_name_invalid'));
                    return;
                }
            } else {
                // Length
                $this->setContent(200, array('status' => 'last_name_length'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400, array('status' => 'last_name_empty'));
            return;
        }

        // Check if email is set
        if (isset($json['email'])) {
            // Trim
            $email = trim($json['email'], ' ');

            // Check if valid email
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Sanitize email
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);

                // Check if email is not taken
                if ($this->model->checkEmail(Auth::$ID, $email)) {
                    // Add to values list
                    $values['email'] = $email;
                } else {
                    // Taken
                    $this->setContent(200, array('status' => 'email_taken'));
                    return;
                }
            } else {
                // Invalid
                $this->setContent(200, array('status' => 'email_invalid'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400, array('status' => 'email_empty'));
            return;
        }

        // Check if username is set
        if (isset($json['username'])) {
            // Trim
            $username = trim($json['username'], ' ');

            // Check length
            if (strlen($username) > 0 && strlen($username) < 21) {
                // Check if username is valid
                if (ctype_alnum($username)) {
                    // Check if username is not taken
                    if ($this->model->checkUsername(Auth::$ID, $username)) {
                        // Add to values list
                        $values['username'] = $username;
                    } else {
                        // Taken
                        $this->setContent(200, array('status' => 'username_taken'));
                        return;
                    }
                } else {
                    // Invalid
                    $this->setContent(200, array('status' => 'username_invalid'));
                    return;
                }
            } else {
                // Length
                $this->setContent(200, array('status' => 'username_length'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400, array('status' => 'username_empty'));
            return;
        }

        // Add session ID to values list
        $values['ID'] = Auth::$ID;

        // Update admin's details
        $result = $this->model->updateDetails($values);

        // Check result
        if ($result) {
            // OK
            $this->setContent(200, array('status' => 'success'));
        } else {
            // Internal Server Error
            $this->setContent(500);
        }
    }
}


?>