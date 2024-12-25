<?php

/**
 * User model class extending the base Model class.
 * Handles database operations related to users.
 */
require_once __DIR__ . '/Model.php';

class User extends Model
{
    /**
     * The name of the table associated with this model.
     */
    protected $table = 'ecoUser'; // Table name

    /**
     * Fetches a user from the database by their username.
     * 
     * @param string $username The username of the user to fetch.
     * @return array|false The user data as an associative array or false if not found.
     */
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
