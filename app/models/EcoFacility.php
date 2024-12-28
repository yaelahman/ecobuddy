<?php

require_once __DIR__ . '/Model.php';

/**
 * EcoFacility model class.
 * 
 * This class extends the Model class and is used to interact with the 'ecoFacilities' table in the database.
 * 
 * @property string $table The name of the table in the database.
 */
class EcoFacility extends Model
{
    protected $table = 'ecoFacilities'; // Table name

    /**
     * Fetch all ecoFacilities with pagination, search and order.
     * 
     * Executes a SELECT query to fetch all ecoFacilities from the table with pagination, search and order.
     * 
     * @param int $start The start index for pagination.
     * @param int $length The number of records to fetch.
     * @param string $search The search string to filter records.
     * @param string $orderColumn The column to order by.
     * @param string $orderDirection The direction of the order.
     * @return array An array of ecoFacilities fetched from the table.
     */
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

    /**
     * Get the total number of ecoFacilities.
     * 
     * Executes a SELECT query to fetch the total number of ecoFacilities from the table.
     * 
     * @return int The total number of ecoFacilities.
     */
    public function getTotalRecords()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM $this->table");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    /**
     * Get the total number of filtered ecoFacilities.
     * 
     * Executes a SELECT query to fetch the total number of ecoFacilities from the table based on a search filter.
     * 
     * @param string $search The search string to filter records.
     * @return int The total number of filtered ecoFacilities.
     */
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

    /**
     * Deletes all eco facilities.
     */
    public function deleteAll()
    {
        $stmt = $this->db->prepare("DELETE FROM $this->table");
        return $stmt->execute();
    }
}
