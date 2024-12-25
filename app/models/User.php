<?php


require_once __DIR__ . '/Model.php';

class User extends Model
{
    protected $table = 'ecoUser'; // Table name

    // Fetch a user by USERNAME
    public function getUserByUsername($username)
    {
        $query = "
            SELECT u.*, e.name AS role 
            FROM $this->table AS u
            LEFT JOIN ecoUsertypes AS e
            ON u.userType = e.id
            WHERE u.username = :username
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
