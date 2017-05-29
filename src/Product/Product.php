<?php

class Product extends General
{
    const VIEW_PATH = 'Product/views/';

    public function add(){

        if($this->is_post()){

            if($this->addProductValidation()){


                $name = $this->post['name'];
                $amount = $this->post['description'];
                $price = $this->post['price'];
                $inStock = $this->post['in_stock'];

                $sqlStatement = "INSERT INTO Products(name, description, price, in_stock) values ('$name', '$description', '$price', $inStock)";

                $result = $this->getConnection()->query($sqlStatement);

                if ($result) {
                    $this->redirect('/products/index');
                }

            }

            return false;
        }

        $this->render(Product::VIEW_PATH . 'add.html');
    }

    public function edit(string $id) {

    }

    public function delete(string $id) {

    }

    public function index(){

        $result = $this->getConnection()->query('SELECT * FROM products');

        while ($row = mysqli_fetch_row($result)) {
            $data['products'][] = $row;
        }

        $this->render(Product::VIEW_PATH . 'index.php',  $data);
    }

    private function addProductValidation()
    {
        return true;
    }


}
