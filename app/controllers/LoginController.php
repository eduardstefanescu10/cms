<?php


namespace App\Controllers;
use App\Models\AccountModel;
use CMS\Auth\Auth;


class LoginController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\AccountModel()
     */
    private $model;

    /**
     * LoginController constructor
     */
    public function __construct()
    {
         $this->model = new AccountModel();
    }

    /**
     * The default action
     */
    public function index()
    {
        // Check if logged
        if (!Auth::checkLogin()) {
            // Get view
            view('login');
        } else {
            // Redirect to dashboard
            redirect('');
        }
    }

    /**
     * Log in the admin
     */
    public function login()
    {
        // Check if POST request is set
        if (isset($_POST)) {
            // Get json
            $json = json_decode(file_get_contents("php://input"), true);

            // Check if not empty
            if (!empty($json)) {
                // Check if values exists
                if (isset($json['username']) && isset($json['password']) && isset($json['remember'])) {
                    // Destroy session
                    session_destroy();

                    // Sanitize values
                    $username = sanitizeUsername($json['username']);
                    $password = cryptPass($json['password']);

                    // Check if the value is "yes"
                    if ($json['remember'] == 'yes') {
                        $remember = true;
                    } else {
                        $remember = false;
                    }

                    // Log in admin
                    $result = $this->model->login($username, $password, $remember);

                    // Check result
                    if ($result) {
                        // OK
                        $this->setContent(200);
                    } else {
                        // Unauthorized
                        $this->setContent(401);
                    }
                } else {
                    // Bad request
                    $this->setContent(400);
                }
            } else {
                // Bad request
                $this->setContent(400);
            }
        } else {
            // Bad request
            $this->setContent(400);
        }
    }
}



?>