<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CustomerModel;

class StaffDashboard extends BaseStaffController
{
    /**
     * Displays the main staff dashboard (the cart view).
     */
    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();

        return view('staff_dashboard', $data);
    }

    /**
     * Handles the checkout form submission using a standard redirect method.
     */
    public function process_sale()
    {
        // Get form data from the POST request.
        $cart_items = $this->request->getPost('cart_items');
        $customer_name = $this->request->getPost('customer_name');
        $customer_number = $this->request->getPost('customer_number');
        $customer_address = $this->request->getPost('customer_address');
        $payment_received = $this->request->getPost('payment_received');

        // Basic validation.
        if (empty($cart_items)) {
            return redirect()->back()->with('error', 'The cart cannot be empty.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Start a database transaction for data integrity.

        try {
            $itemModel = new ItemModel();
            $customerModel = new CustomerModel();
            $saleModel = new SaleModel();
            $saleItemModel = new SaleItemModel();

            $totalPrice = 0;
            $customerId = null;

            // 1. Create customer if details are provided.
            if (!empty($customer_name)) {
                $customerId = $customerModel->insert([
                    'customer_name'    => $customer_name,
                    'customer_number'  => $customer_number,
                    'customer_address' => $customer_address,
                ]);
            }

            // 2. Calculate total price and validate stock from the submitted cart data.
            foreach ($cart_items as $cartItem) {
                $item = $itemModel->find($cartItem['id']);
                if (!$item || $item['item_quantity'] < $cartItem['quantity']) {
                    throw new \Exception('Item ' . ($item['item_name'] ?? 'ID:'.$cartItem['id']) . ' is out of stock or does not exist.');
                }
                $totalPrice += $cartItem['price'] * $cartItem['quantity'];
            }

            // 3. Create the main sale record.
            $saleId = $saleModel->insert([
                'customer_id'      => $customerId,
                'staff_id'         => session()->get('staff_id'),
                'total_price'      => $totalPrice,
                'payment_received' => $payment_received,
                'change_due'       => $payment_received - $totalPrice,
                'sale_date'        => date('Y-m-d H:i:s'),
            ]);

            // 4. Create sale items and update inventory.
            foreach ($cart_items as $cartItem) {
                $saleItemModel->insert([
                    'sale_id'    => $saleId,
                    'item_id'    => $cartItem['id'],
                    'quantity'   => $cartItem['quantity'],
                    'item_price' => $cartItem['price'],
                    'subtotal'   => $cartItem['price'] * $cartItem['quantity'],
                ]);

                // Decrease the stock quantity.
                $itemModel->set('item_quantity', 'item_quantity - ' . $cartItem['quantity'], false)
                    ->where('item_id', $cartItem['id'])
                    ->update();
            }

            $db->transComplete(); // Finalize the transaction.

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed.');
            }

            // On success, redirect to the new receipt page with a success message.
            return redirect()->to('staff/receipt/' . $saleId)->with('success', 'Sale completed successfully!');

        } catch (\Exception $e) {
            $db->transRollback(); // Rollback any database changes on error.

            // On error, redirect back to the dashboard with the error message.
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Displays the receipt for a specific sale.
     */
    public function receipt($sale_id)
    {
        $saleModel = new \App\Models\SaleModel();
        $saleDetails = $saleModel->getSaleDetails($sale_id);

        if (empty($saleDetails)) {
            return redirect()->to('staff/dashboard')->with('error', 'Sale not found.');
        }

        $data['sale'] = $saleDetails;

        return view('receipt', $data);
    }
}