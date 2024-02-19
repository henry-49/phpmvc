<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;

// Product Model class
class Product extends Model
{
        protected $table = "product";

        protected $errors = [];

         protected function addError(string $field, string $message): void
         {
                $this->errors[$field] = $message;
         }

        // method to validate record be saving to database
        protected function validate(array $data): bool 
        {
               if (empty($data["name"])){
                
                        $this->addError("name", "name is required");
               } 
               
               return empty($this->errors);
        }


        public function getErrors(): array
        {
                return $this->errors;
        }

        
}