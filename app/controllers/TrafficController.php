<?php


namespace App\Controllers;
use App\Models\TrafficModel;
use CMS\Auth\Auth;


class TrafficController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\TrafficsModel
     */
    private $model;

    /**
     * TrafficController constructor
     */
    public function __construct()
    {
        $this->model = new TrafficModel();
    }

    /**
     * Get views for a specific interval
     */
    public function getDaysViews()
    {
        // Empty array
        $daysViews = [];

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

        // Check if days is set
        if (isset($json['days'])) {
            // Check if integer
            if (is_int($json['days'])) {
                // Check that is greater than 0
                if ($json['days'] > 0) {
                    // Loop days
                    for ($i = 0; $i < $json['days']; $i++) {
                        // Check if $i is 0
                        if ($i == 0) {
                            $day = date('Y-m-d');
                        } else {
                            $day = strtotime(date('Y-m-d'));
                            $day = date('Y-m-d', strtotime('-' . $i . ' day', $day));
                        }

                        // Get day views
                        $views = $this->model->getDayViews($day);

                        // Add views to final array
                        $daysViews[$day] = $views;
                    }

                    // OK
                    $this->setContent(200, $daysViews);
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
    }
}