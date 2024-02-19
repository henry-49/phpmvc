<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;

// Product Model class
class Product extends Model
{
        protected $table = "product";

        // method to validate record be saving to database
        protected function validate(array $data): bool 
        {
               if (empty($data["name"])){
                        return false;
               } 
               
               return true;
        }

        
}