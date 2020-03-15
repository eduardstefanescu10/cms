<?php


namespace App\Models;


Class ProductsModel extends Model
{
    /**
     * Add new product to the database
     *
     * @param array $request
     *
     * @return int
     */
    public function add($request = array())
    {
        $request['created'] = date('Y-m-d H:s');

        return $this->create('cms_products', $request);
    }

    /**
     * Get product from database
     *
     * @param int $ID
     *
     * @return null|array
     */
    public function retrieve(int $ID)
    {
        $sql = "SELECT * FROM cms_products WHERE ID=:ID";
        $query = $this->get(
            $sql,
            [
                'ID' => $ID
            ],
            1
        );

        // Check result
        if ($query != null) {
            return $query[0];
        } else {
            return null;
        }
    }

    /**
     * Edit product
     *
     * @param array $request
     *
     * @return bool
     */
    public function edit($request = array())
    {
        $result = $this->update(
            'cms_products',
            [
                'name'    => $request['name'],
                'price'   => $request['price'],
                'updated' => date('Y-m-d H:s')
            ],
            [
                'ID' => $request['ID']
            ],
            'LIMIT 1'
        );

        // Check result
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete product
     *
     * @param int $ID
     *
     * @return bool
     */
    public function remove(int $ID)
    {
        $result = $this->delete(
            'cms_products',
            'ID=' . $ID,
            1
        );

        // Check result
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }
}


?>