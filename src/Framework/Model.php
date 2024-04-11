<?php

declare(strict_types=1);

namespace Framework;

use App\Database;
use PDO;


// Base Model class
// make it abstract as we won't be creating any objects from the class
abstract class Model 
{
    // ?string nullable convatation  ommited
    protected  $table;

    protected $errors = [];

    public function update($id, array $data): bool
    {

        // remove id in the array elements be processing the data
        unset($data['id']);
        
        // prevent empty record to database
        $this->validate($data);

        if (!empty($this->errors)) {
            return false;
        };


        $sql = "UPDATE {$this->getTable()}";

      // get an array of the column names from data using array key function
      // & is use to passed argument by reference
        $assignments = array_keys($data);
        array_walk($assignments, function(&$value) { 
            $value = "$value = ?";
        });

        $sql .= " SET " . implode(",", $assignments);
        
        $sql .= " WHERE id = ?";

        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($sql);

        $i = 1;

        foreach ($data as $value) {

            $type = match (gettype($value)) {
                    "boolean" => PDO::PARAM_BOOL,
                    "integer" => PDO::PARAM_INT,
                    "NULL" => PDO::PARAM_NULL,
                    default => PDO::PARAM_STR
                };

            $stmt->bindValue($i++, $value, $type);
        }
        
        // bind values for id
        $stmt->bindValue($i++, $id, PDO::PARAM_INT);
        
        return $stmt->execute();

        // exit($sql);
       // return true;
    }

    protected function validate(array $data): void
    {   
    }
    
    public function getInsertID(): string
    {
        $conn = $this->database->getConnection();
        
        return $conn->lastInsertId();
    }
    
    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
    
    private function getTable(): string
    {
        // check if table is not null
        if ($this->table !== null) {
        return $this->table;
        }
        
        // get the child class
       $path = explode("\\", $this::class);
       
      return strtolower(array_pop($path));
    }
    
    // model dependens on database object
    public function __construct(protected Database $database)
    {
    }
    
    public function findAll(): array 
    {

        $pdo = $this->database->getConnection();
        
        $sql = "SELECT * FROM {$this->getTable()}";
        
        // get data from the database
        $stmt = $pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function find(string $id): array|bool
    {
        $conn = $this->database->getConnection();
        
        $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
        

    public function insert(array $data): bool
    {
        // prevent empty record to database
        $this->validate($data);
        
       if (! empty($this->errors)) {
            return false;
       };
        
        $columns = implode(", ", array_keys($data));

        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
       // INSERT INTO product (name, description) VALUES (?, ?)
       
        $sql = "INSERT INTO {$this->getTable()} ($columns) 
                VALUES ($placeholders)";
        
        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($sql);

        $i = 1;
        
        foreach ($data as $value) {
            
            $type = match(gettype($value)){
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            
            $stmt->bindValue($i++, $value, $type);
        }

        return $stmt->execute();
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM {$this->getTable()} 
                WHERE id = :id";

        $conn = $this->database->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}