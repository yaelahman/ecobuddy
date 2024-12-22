<?php
class User
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance(); // Use the singleton database instance
        $this->table = "ecoUser"; // Name of the table
    }

    // Fetch all users
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM $this->table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO $this->table (username, password, userType) VALUES (:username, :password, :userType)");
        return $stmt->execute($data);
    }

    // Fetch a user by ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user information
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE $this->table SET username = :username, password = :password, userType = :userType WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    // Delete a user by ID
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM $this->table WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
