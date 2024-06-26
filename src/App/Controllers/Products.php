<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Controller;
use Framework\Exceptions\PageNotFoundException;

// Product Controller Class Definition
class Products extends Controller
{

    // products depends on model object
    public function __construct(private Product $model)
    {
    }

    public function index()
    {
        // require "src/models/product.php";

       // $model = new Product;

        $products = $this->model->findAll();

        //$viewer = new Viewer;

        // echo $this->viewer->render("shared/header.php", [
            
        // ]);

        echo  $this->viewer->render("Products/index.mvc.php", [
            "products" => $products,
            "total" => $this->model->getTotal()
        ]);
        
       // require "views/products_index.php";
    }

    public function show(string $id)
    {
        $product = $this->getProduct($id);

        //var_dump($id);

        //require "views/products_show.php";

       // $viewer = new Viewer;

        // echo $this->viewer->render("shared/header.php", [
        //     "title" => "Show Product"
        // ]);
        
        echo  $this->viewer->render("Products/show.mvc.php",
            [
                "product" => $product
            ]
        );
    }

    private function getProduct(string $id): array
    {
        $product = $this->model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
        }

        return $product;
    }
    
    public function edit(string $id)
    {
        $product = $this->getProduct($id);

        //var_dump($id);

        //require "views/products_show.php";

        // $viewer = new Viewer;

        echo $this->viewer->render("shared/header.php", [
            "title" => "Edit Product"
        ]);

        echo  $this->viewer->render(
            "Products/edit.php",
            [
                "product" => $product
            ]
        );
    }
    
    public function showPage(string $title, string $id, string $page)
    {
        
        echo $title, " ", $id, " ", $page;
    }


    public function new()
    {
        
        // echo $this->viewer->render("shared/header.php", [
        //     "title" => "New Product"
        // ]);

        echo  $this->viewer->render("Products/new.mvc.php");
    }

    public function create()
    {
        $data = [
            "name" => $this->request->post["name"],
            "description" => empty($this->request->post["description"]) ? null : $this->request->post["description"],
        ];

        if ($this->model->insert($data)){
            
            header("Location: /products/{$this->model->getInsertID()}/show");
            exit;
                        
        }else{

            // echo $this->viewer->render("shared/header.php", [
            //     "title" => "New Product"
            // ]);

            echo  $this->viewer->render("Products/new.mvc.php", [
                "errors" => $this->model->getErrors(),
                "product" => $data
            ]);
        }
        
    }

    public function update(string $id)
    {
        $product = $this->getProduct($id);

        /*  $data = [
            "name" => $_POST["name"],
            "description" => empty($_POST["description"]) ? null : $_POST["description"],
        ]; */


        $product["name"] = $this->request->post["name"];
        $product["description"] = empty($this->request->post["description"]) ? null : $this->request->post["description"];
        

        if ($this->model->update($id, $product)) {

            header("Location: /products/{$id}/show");
            exit;
            
        } else {

            echo $this->viewer->render("shared/header.php", [
                "title" => "Edit Product"
            ]);

            echo  $this->viewer->render("Products/edit.php", [
                "errors" => $this->model->getErrors(),
                "product" => $product
            ]);
        }
    }

    public function delete(String $id) 
    {
        $product = $this->getProduct($id);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            
            $this->model->delete($id);
            
            // redirect after delete
            header("Location: /products/index");
            exit;
        }

        echo $this->viewer->render("shared/header.php", [
            "title" => "Delete Product"
        ]);
        
        echo  $this->viewer->render("Products/delete.php", [
            "product" => $product
        ]);
    }

    public function destroy(string $id) 
    {
        $product = $this->getProduct($id);

            $this->model->delete($id);

            // redirect after delete
            header("Location: /products/index");
            exit;
        
    }
}