<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table            = 'sales';
    protected $primaryKey       = 'sale_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Define the fields that are allowed to be saved.
    protected $allowedFields    = [
        'customer_id',
        'staff_id',
        'total_price',
        'payment_received',
        'change_due',
        'sale_date'
    ];
}