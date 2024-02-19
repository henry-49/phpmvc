<?php

declare(strict_types=1);

namespace Framework;

use App\Database;
use PDO;


// Product Model class
// make it abstract as we won't be creating any objects from the class
abstract class Model 
{
    // model dependens ob database object
    public function __construct(private Database $database)
    {
    }
    
    public function findAll(): array 
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