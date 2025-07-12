<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CustomerModel;

class SalesReport extends BaseOwnerController
{
    /**
     * Displays the main sales report page.
     */
    public function index()
    {
        $saleModel = new SaleModel();
        $data['sales'] = $saleModel
            ->select('sales.*, customers.customer_name')
            ->join('customers', 'customers.customer_id = sales.customer_id', 'left')
            ->orderBy('sales.sale_date', 'DESC')
            ->findAll();

        return view('sales_report', $data);
    }

    /**
     * Displays a detailed receipt for a single sale.
     *
     * @param int $saleId The ID of the sale.
     */
    public function viewReceipt($saleId)
    {
        $saleModel = new \App\Models\SaleModel();
        $saleItemModel = new \App\Models\SaleItemModel();
        $customerModel = new \App\Models\CustomerModel();

        // Fetch the main sale record
        $sale = $saleModel->find($saleId);
        if (!$sale) {
            return redirect()->to('/staff/dashboard')->with('error', 'Sale not found.');
        }

        // Fetch the items for this sale and join with item names
        $sale['items'] = $saleItemModel
            ->select('sale_items.*, items.item_name')
            ->join('items', 'items.item_id = sale_items.item_id')
            ->where('sale_items.sale_id', $saleId)
            ->findAll();

        // Fetch customer name if a customer was linked to the sale
        $sale['customer_name'] = 'N/A';
        if ($sale['customer_id']) {
            $customer = $customerModel->find($sale['customer_id']);
            if ($customer) {
                $sale['customer_name'] = $customer['customer_name'];
            }
        }

        return view('receipt', ['sale' => $sale]);
    }
}