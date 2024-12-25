<?php

/**
 * Base Model class for database operations.
 * 
 * This class provides a basic structure for CRUD operations on a database table.
 * It assumes the use of a singleton Database class for PDO instance management.
 * 
 * @property string $table The name of the table associated with this model.
 * @property string $primaryKey The primary key of the table.
 * @property PDO $db The PDO instance for database operations.
 */
class Model
{
    protected $table; // The name of the table
    protected $primaryKey = 'id'; // Default primary key
    protected $db; // PDO instance

    /**
     * Constructor to initialize the model with a PDO instance.
     */
    public function __construct()
    {
        $this->db = Database::getInstance(); // Assuming a Database class with a singleton instance of PDO
    }

    /**
     * Retrieve all records from the associated table.
     * 
     * Executes a SELECT query to fetch all records from the table and returns them as an array.
     * 
     * @return array An array of records fetched from the table.
     */
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a record by its primary key.
     * 
     * Prepares and executes a SELECT query to find a record by its primary key.
     * 
     * @param int $id The primary key of the record to find.
     * @return array|null The found record or null if not found.
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve records based on a WHERE clause.
     * 
     * Prepares and executes a SELECT query with a WHERE clause to filter records.
     * 
     * @param string $column The column name for the WHERE clause.
     * @param string $operator The operator for the WHERE clause.
     * @param mixed $value The value for the WHERE clause.
     * @return array An array of records that match the WHERE clause.
     */
    public function where($column, $operator, $value)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new record into the table.
     * 
     * Prepares and executes an INSERT query to add a new record to the table.
     * 
     * @param array $data The data to be inserted into the table.
     * @return bool Returns true on successful execution, false otherwise.
     */
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

    /**
     * Update a record by its primary key.
     * 
     * Prepares and executes an UPDATE query to modify a record based on its primary key.
     * 
     * @param int $id The primary key of the record to update.
     * @param array $data The data to update the record with.
     * @return bool Returns true on successful execution, false otherwise.
     */
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

    /**
     * Delete a record by its primary key.
     * 
     * Prepares and executes a DELETE query to remove a record based on its primary key.
     * 
     * @param int $id The primary key of the record to delete.
     * @return bool Returns true on successful execution, false otherwise.
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
