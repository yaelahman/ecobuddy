<?php

require_once __DIR__ . '/Model.php';

class EcoFacility extends Model
{
    protected $table = 'ecoFacilities'; // Table name

    // Fetch all users
    public function getAll($start = 0, $length = 10, $search = '', $orderColumn = 'id', $orderDirection = 'DESC')
    {
        // Base query with join to ecoFacilityStatus
        $query = "SELECT $this->table.*, ecoFacilityStatus.isVisited, ecoFacilityStatus.statusComment FROM $this->table LEFT JOIN ecoFacilityStatus ON $this->table.id = ecoFacilityStatus.facilityId AND ecoFacilityStatus.contributor = :userId";

        // Add search filter if provided
        if (!empty($search)) {
            $query .= " WHERE $this->table.title LIKE :search OR $this->table.description LIKE :search OR $this->table.county LIKE :search OR $this->table.town LIKE :search";
        }

        // Add ordering
        $query .= " ORDER BY $orderColumn $orderDirection";

        // Add pagination
        $query .= " LIMIT :start, :length";

        // Prepare statement
        $stmt = $this->db->prepare($query);

        // Bind parameters
        if (isset($_SESSION['user_id']))
            $stmt->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);

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
}
