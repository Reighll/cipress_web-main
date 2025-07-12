<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table            = 'items';
    protected $primaryKey       = 'item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Define the fields that are allowed to be saved.
    protected $allowedFields    = [
        'item_name',
        'item_category',
        'item_quantity',
        'item_initial_price',
    ];

    // Enable automatic created_at and updated_at timestamps.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}