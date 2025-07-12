<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'customer_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Define the fields that are allowed to be saved.
    protected $allowedFields    = [
        'customer_name',
        'customer_number',
        'customer_address',
    ];

    // Enable automatic created_at and updated_at timestamps.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}