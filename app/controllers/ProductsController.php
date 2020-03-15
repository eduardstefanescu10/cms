<?php


namespace App\Controllers;
use App\Models\ProductsModel;


class ProductsController extends Controller
{
    /**
     * The model
     *
     * @var \App\Models\ProductsModel
     */
    private $model;

    public function __construct()
    {
        $this->model = new ProductsModel();
    }

    /**
     * Create new product
     */
    public function create()
    {
        // Check if POST
        if (isset($_POST)) {
            // Get json
            $json = json_decode(file_get_contents("php://input"), true);

            // Check if json is not empty
            if (!empty($json)) {
                $result = $this->model->add($json);

                // Check result
                if ($result > 0) {
                    $this->setContent(200, array('ID' => $result));
                } else {
                    $this->setContent(500);
                }
            } else {
                $this->setContent(400);
            }
        } else {
            $this->setContent(405);
        }
    }

    /**
     * Get products list
     *
     * @param array $request
     */
    public function read($request = array())
    {
        // Check if GET
        if (isset($_GET)) {
            // Check if ID is set
            if (isset($request['ID'])) {
                $result = $this->model->retrieve($request['ID']);

                // Check result
                if ($result != null) {
                    $this->setContent(200, $result);
                } else {
                    $this->setContent(404);
                }
            } else {
                $this->setContent(400);
            }
        } else {
            $this->setContent(405);
        }
    }

    /**
     * Update existing product
     */
    public function update()
    {
        // Check if POST
        if (isset($_POST)) {
            // Get json
            $json = json_decode(file_get_contents("php://input"), true);

            // Check if json is not empty
            if (!empty($json)) {
                $result = $this->model->edit($json);

                // Check result
                if ($result == true) {
                    $this->setContent(200);
                } else {
                    $this->setContent(500);
                }
            } else {
                $this->setContent(400);
            }
        } else {
            $this->setContent(405);
        }
    }

    /**
     * Delete existing product
     */
    public function delete()
    {
        // Check if POST
        if (isset($_POST)) {
            // Get json
            $json = json_decode(file_get_contents("php://input"), true);

            // Check if json is not empty
            if (!empty($json)) {
                $result = $this->model->remove($json['ID']);

                // Check result
                if ($result == true) {
                    $this->setContent(200);
                } else {
                    $this->setContent(500);
                }
            } else {
                $this->setContent(400);
            }
        } else {
            $this->setContent(405);
        }
    }
}


?>