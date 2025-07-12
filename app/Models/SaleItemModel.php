<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table            = 'sale_items';
    protected $primaryKey       = 'sale_item_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Define the fields that are allowed to be saved.
    protected $allowedFields    = [
        'sale_id',
        'item_id',
        'quantity',
        'item_price',
        'subtotal'
    ];
}