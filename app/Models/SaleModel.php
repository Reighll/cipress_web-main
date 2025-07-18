<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $allowedFields = ['customer_id', 'staff_id', 'total_price', 'payment_received', 'change_due', 'sale_date'];

    /**
     * [NEW] Calculates the sum of 'total_price' for a given date range.
     */
    public function getTotalSalesForPeriod($start_date, $end_date)
    {
        if (empty($start_date) || empty($end_date)) {
            return 0;
        }

        $result = $this->selectSum('total_price', 'total')
            ->where('sale_date >=', $start_date)
            ->where('sale_date <=', $end_date)
            ->get()
            ->getRow();

        return $result->total ?? 0;
    }

    /**
     * [NEW] Fetches all sales within a date range and includes all associated items for each sale.
     */
    public function getDetailedSalesReport($start_date, $end_date)
    {
        // First, get all sales within the date range, joining staff name
        $sales = $this->select("sales.*, staff.staff_username as staff_name")
            ->join('staff', 'staff.staff_id = sales.staff_id', 'left')
            ->where('sale_date >=', $start_date)
            ->where('sale_date <=', $end_date)
            ->orderBy('sale_date', 'DESC')
            ->findAll();

        if (empty($sales)) {
            return [];
        }

        $saleItemModel = model(SaleItemModel::class);

        // For each sale, attach its list of items sold
        foreach ($sales as &$sale) {
            $sale['items'] = $saleItemModel
                ->select('sale_items.*, items.item_name, items.item_initial_price')
                ->join('items', 'items.item_id = sale_items.item_id', 'left')
                ->where('sale_id', $sale['sale_id'])
                ->findAll();
        }

        return $sales;
    }

    /**
     * [UNCHANGED]
     * Fetches all details for a single sale, including customer info and all items.
     * This is the original method for the working receipt page.
     */
    public function getSaleDetails($sale_id)
    {
        $sale = $this->select("sales.*, customers.customer_name, customers.customer_number, customers.customer_address, staff.staff_username as staff_name")
            ->join('customers', 'customers.customer_id = sales.customer_id', 'left')
            ->join('staff', 'staff.staff_id = sales.staff_id', 'left')
            ->find($sale_id);

        if ($sale) {
            $sale['items'] = model(SaleItemModel::class)
                ->select('sale_items.*, items.item_name, items.item_initial_price')
                ->join('items', 'items.item_id = sale_items.item_id')
                ->where('sale_id', $sale['sale_id'])
                ->findAll();
        }

        return $sale;
    }
}