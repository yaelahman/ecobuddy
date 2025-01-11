<?php

/**
 * User model class extending the base Model class.
 * Handles database operations related to users.
 */
require_once __DIR__ . '/Model.php';

class EcoUserTypes extends Model
{
    /**
     * The name of the table associated with this model.
     */
    protected $table = 'ecoUsertypes'; // Table name

}
