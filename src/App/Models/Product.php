<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;

// Product Model class
class Product extends Model
{
        protected $table = "product";

        // method to validate record be saving to database
        protected function validate(array $data): void 
        {
               if (empty($data["name"])){
                
                        $this->addError("name", "name is required");
               } 
               
        }

        // return total number in the product table
        public function getTotal(): int
        {
                $sql = "SELECT COUNT(*) as total
                        FROM product";

                $conn = $this->database->getConnection();

                $stmt = $conn->query($sql);

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                return (int) $row["total"];
        }
                        
        
}