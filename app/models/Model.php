<?php

class Model
{
    protected $table; // The name of the table
    protected $primaryKey = 'id'; // Default primary key
    protected $db; // PDO instance

    public function __construct()
    {
        $this->db = Database::getInstance(); // Assuming a Database class with a singleton instance of PDO
    }

    // Get all records
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find a record by primary key
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get records with a WHERE clause
    public function where($column, $operator, $value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new record
    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute();
    }

    // Update a record by primary key
    public function update($id, $data)
    {
        $setClause = implode(', ', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $query = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }

        return $stmt->execute();
    }

    // Delete a record by primary key
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
