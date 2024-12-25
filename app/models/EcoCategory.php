<?php

/**
 * EcoCategory model class.
 * 
 * This class represents the EcoCategory model, extending the base Model class.
 * It defines the table name for the EcoCategory model.
 * 
 * @property string $table The name of the table in the database.
 */
require_once __DIR__ . '/Model.php';

class EcoCategory extends Model
{
    /**
     * The name of the table in the database.
     * 
     * @var string
     */
    protected $table = 'ecoCategories'; // Table name
}
