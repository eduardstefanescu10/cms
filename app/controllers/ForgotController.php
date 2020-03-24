<?php


namespace App\Controllers;
use App\Models\AccountModel;
use CMS\Auth\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class ForgotController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\AccountModel()
     */
    private $model;

    /**
     * ForgotController constructor
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
            view('forgot');
        } else {
            // Redirect to dashboard
            redirect();
        }
    }

    /**
     * Recover password
     */
    public function forgot()
    {
        // Check if POST request is set
        if (isset($_POST)) {
            // Get json
            $json = json_decode(file_get_contents("php://input"), true);

            // Check if not empty
            if (!empty($json)) {
                // Check if values exists
                if (isset($json['email'])) {
                    // Sanitize email
                    $email = sanitizeEmail($json['email']);

                    // Generate random password
                    $randomPass = getRandomPass();

                    // Crypt password
                    $passCrypt = cryptPass($randomPass);

                    // Update temporarily password
                    $result = $this->model->forgot($email, $passCrypt);

                    // Check result
                    if ($result > 0) {
                        // Get ID
                        $ID = $result;

                        // Instantiation and passing 'true' enables exceptions
                        $mail = new PHPMailer(true);

                        try {
                            //Server settings
                            $mail->SMTPDebug = 0; // 2
                            $mail->isSMTP();
                            $mail->Host       = OPTIONS['SERVER_MAIL_NAME'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = OPTIONS['SERVER_MAIL_USER'];
                            $mail->Password   = OPTIONS['SERVER_MAIL_PASS'];
                            $mail->SMTPSecure = OPTIONS['SERVER_MAIL_ENCRYPTION'];
                            $mail->Port       = OPTIONS['SERVER_MAIL_PORT'];

                            // Recipients
                            $mail->setFrom(OPTIONS['SERVER_EMAIL_ADDRESS'], OPTIONS['SITE_NAME']);
                            $mail->addAddress($email, '');

                            // Content
                            $mail->isHTML(true);

                            $mail->Subject = 'Recover your password';

                            // Get mail content
                            $mail->Body = bufferView(
                                'mails/en_US/forgot',
                                [
                                    'url'        => OPTIONS['URL'],
                                    'ID'         => $ID,
                                    'passCrypt'  => $passCrypt,
                                    'randomPass' => $randomPass
                                ]
                            );

                            // Send mail
                            $mail->send();

                            // OK
                            $this->setContent(200, array('status' => 'success'));
                        } catch (Exception $e) {
                            // Internal Server Error
                            $this->setContent(500);
                        }
                    } elseif ($result == 0) {
                        // OK
                        $this->setContent(200, array('status' => 'not_found'));
                    } else {
                        // Internal Server Error
                        $this->setContent(500);
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

    /**
     * Reset password
     *
     * @param array $request
     */
    public function resetPass($request = array())
    {
        // Check if ID or temPass are not set
        if (!isset($request['ID']) || !isset($request['tempPass'])) {
            redirect('404');
        }

        // Check if ID or tempPass are empty
        if (empty($request['ID']) || empty($request['tempPass'])) {
            redirect('404');
        }

        // Sanitize values
        $ID = (int) $request['ID'];
        $tempPass = sanitizeString($request['tempPass']);

        // Update password
        $result = $this->model->updateTempPass($ID, $tempPass);

        // Check result
        if ($result) {
            // Success
            redirect('login');
        } else {
            // Failed
            redirect('404');
        }
    }
}


?>