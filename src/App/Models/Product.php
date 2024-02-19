<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Database;

// Product Model class
class Product 
{
    // model dependens ob database object
    public function __construct(private Database $database)
    {
    }
    
    public function getDate(): array 
    {

        $pdo = $this->database->getConnection();
        // get data from the database
        $stmt = $pdo->query("SELECT * FROM product");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function find(string $id): array|bool
    {
        $conn = $this->database->getConnection();
        
        $sql = "SELECT * FROM product WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        
}