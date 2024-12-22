<?php
class EcoFacility
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance(); // Use the singleton database instance
        $this->table = "ecoFacilities"; // Name of the table
    }

    // Fetch all users
    public function getAll($start = 0, $length = 10, $search = '', $orderColumn = 'id', $orderDirection = 'ASC')
    {
        // Base query
        $query = "SELECT * FROM $this->table";

        // Add search filter if provided
        if (!empty($search)) {
            $query .= " WHERE title LIKE :search OR description LIKE :search OR county LIKE :search OR town LIKE :search";
        }

        // Add ordering
        $query .= " ORDER BY $orderColumn $orderDirection";

        // Add pagination
        $query .= " LIMIT :start, :length";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Bind parameters
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalRecords()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM $this->table");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getFilteredRecords($search)
    {
        $query = "SELECT COUNT(*) as count FROM $this->table";

        // Add search filter if provided
        if (!empty($search)) {
            $query .= " WHERE title LIKE :search OR description LIKE :search OR county LIKE :search OR town LIKE :search";
        }

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
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
