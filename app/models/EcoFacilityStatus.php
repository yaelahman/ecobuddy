<?php

/**
 * EcoFacilityStatus model class.
 * 
 * This class extends the Model class and is used to interact with the 'ecoFacilityStatus' table in the database.
 * 
 * @property string $table The name of the table in the database.
 */
require_once __DIR__ . '/Model.php';

class EcoFacilityStatus extends Model
{
    protected $table = 'ecoFacilityStatus'; // Table name
}
