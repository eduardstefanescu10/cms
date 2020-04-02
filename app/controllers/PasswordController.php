<?php


namespace App\Controllers;
use App\Models\AccountModel;
use CMS\Auth\Auth;


class PasswordController extends Controller
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
            view('password');
        } else {
            // Redirect to logout page
            redirect('logout');
        }
    }

    /**
     * Change admin's password
     */
    public function changePass()
    {
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

        // Check if current password is set
        if (isset($json['currentPass'])) {
            // Trim
            $currentPass = trim($json['currentPass'], ' ');

            // Crypt password
            $currentPass = cryptPass($currentPass);

            // Check if matches current password
            if ($currentPass != Auth::$password) {
                // OK
                $this->setContent(200, array('status' => 'pass_current_invalid'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check if new password is set
        if (isset($json['newPass'])) {
            // Trim
            $newPass = trim($json['newPass'], ' ');

            // Check if new password has at least 8 chars in length
            if (strlen($newPass) > 7) {
                // Check if new password has less than 50 chars in length
                if (strlen($newPass) < 51) {
                    // Check if new password has both digits and letters
                    if (!ctype_alnum($newPass)) {
                        // Crypt password
                        $newPass = cryptPass($newPass);

                        // Check that the current and new password match
                        if ($currentPass == $newPass) {
                            // OK
                            $this->setContent(200, array('status' => 'pass_new_match'));
                            return;
                        }
                    } else {
                        // OK
                        $this->setContent(200, array('status' => 'pass_new_invalid'));
                        return;
                    }
                } else {
                    // OK
                    $this->setContent(200, array('status' => 'pass_new_length_max'));
                    return;
                }
            } else {
                // OK
                $this->setContent(200, array('status' => 'pass_new_length_min'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Check if confirm password is set
        if (isset($json['confirmPass'])) {
            // Trim
            $confirmPass = trim($json['confirmPass'], ' ');

            // Crypt password
            $confirmPass = cryptPass($confirmPass);

            // Check if confirm and new password don't match
            if ($confirmPass != $newPass) {
                // OK
                $this->setContent(200, array('status' => 'pass_confirm_match'));
                return;
            }
        } else {
            // Bad request
            $this->setContent(400);
            return;
        }

        // Change password
        $result = $this->model->changePass(Auth::$ID, $newPass);

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