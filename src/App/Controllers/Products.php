<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;
use Framework\Viewer;

// Product Controller Class Definition
class Products
{
    // products depends on model object
    public function __construct(private Viewer $viewer, private Product $model)
    {
    }

    public function index()
    {
        // require "src/models/product.php";

       // $model = new Product;

        $products = $this->model->findAll();

        //$viewer = new Viewer;

        echo $this->viewer->render("shared/header.php", [
            "title" => "Product"
        ]);

        echo  $this->viewer->render("Products/index.php", [
            "products" => $products
        ]);
        
       // require "views/products_index.php";
    }

    public function show(string $id)
    {
        $product = $this->model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
        }
        
        //var_dump($id);

        //require "views/products_show.php";

       // $viewer = new Viewer;

        echo $this->viewer->render("shared/header.php", [
            "title" => "Product"
        ]);
        
        echo  $this->viewer->render("Products/show.php",
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
        
        echo $this->viewer->render("shared/header.php", [
            "title" => "New Product"
        ]);

        echo  $this->viewer->render("Products/new.php");
    }

    public function create()
    {
        $data = [
            "name" => $_POST["name"],
            "description" => empty($_POST["description"]) ? null : $_POST["description"],
        ];

        if ($this->model->insert($data)){
            
            echo "record successfully created.";
            
        }else{

            echo $this->viewer->render("shared/header.php", [
                "title" => "New Product"
            ]);

            echo  $this->viewer->render("Products/new.php", [
                "errors" => $this->model->getErrors()
            ]);
        }
        
    }
}