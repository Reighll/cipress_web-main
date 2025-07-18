<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $allowedFields = ['customer_id', 'staff_id', 'total_price', 'payment_received', 'change_due', 'sale_date'];

    /**
     * [FIXED]
     * Fetches sales records for a report, using 'staff_username' as the staff's name.
     */
    public function getSalesReport($start_date, $end_date)
    {
        return $this->select("sales.*, customers.customer_name, staff.staff_username as staff_name")
            ->join('customers', 'customers.customer_id = sales.customer_id', 'left')
            ->join('staff', 'staff.staff_id = sales.staff_id', 'left')
            ->where('sale_date >=', $start_date)
            ->where('sale_date <=', $end_date)
            ->findAll();
    }

    /**
     * [FIXED]
     * Fetches all details for a single sale, using 'staff_username' as the staff's name.
     */
    public function getSaleDetails($sale_id)
    {
        // Get the main sale record, joining customer info and using the staff's username as their name
        $sale = $this->select("sales.*, customers.customer_name, customers.customer_number, customers.customer_address, staff.staff_username as staff_name")
            ->join('customers', 'customers.customer_id = sales.customer_id', 'left')
            ->join('staff', 'staff.staff_id = sales.staff_id', 'left')
            ->find($sale_id);

        // If a sale was found, fetch all of its associated items
        if ($sale) {
            $sale['items'] = model(SaleItemModel::class)
                ->select('sale_items.*, items.item_name')
                ->join('items', 'items.item_id = sale_items.item_id')
                ->where('sale_id', $sale_id)
                ->findAll();
        }

        return $sale;
    }
}