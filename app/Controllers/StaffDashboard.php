<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\CustomerModel;
use CodeIgniter\API\ResponseTrait;

class StaffDashboard extends BaseStaffController
{
    use ResponseTrait;

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
     * API endpoint to process the checkout.
     */
    public function processCheckout()
    {
        if (!$this->request->isAJAX()) {
            return $this->failForbidden('Direct access is not allowed.');
        }

        $db = \Config\Database::connect();
        $db->transStart(); // Start a database transaction.

        try {
            $cartData = $this->request->getJSON();
            $cart = $cartData->cart ?? [];
            $customerInfo = $cartData->customer ?? null;
            $paymentReceived = $cartData->payment ?? 0;

            if (empty($cart)) {
                return $this->fail('Cart cannot be empty.');
            }

            $itemModel = new ItemModel();
            $customerModel = new CustomerModel();
            $saleModel = new SaleModel();
            $saleItemModel = new SaleItemModel();

            $totalPrice = 0;
            $customerId = null;

            // 1. Create Customer if provided
            if ($customerInfo && !empty($customerInfo->name)) {
                $customerId = $customerModel->insert([
                    'customer_name'    => $customerInfo->name,
                    'customer_number'  => $customerInfo->number,
                    'customer_address' => $customerInfo->address,
                ]);
            }

            // 2. Calculate total price and check stock
            foreach ($cart as $cartItem) {
                $item = $itemModel->find($cartItem->id);
                if (!$item || $item['item_quantity'] < $cartItem->quantity) {
                    throw new \Exception('Item ' . ($item['item_name'] ?? 'ID:'.$cartItem->id) . ' is out of stock or does not exist.');
                }
                $totalPrice += $cartItem->price * $cartItem->quantity;
            }

            // 3. Create Sale record
            $saleId = $saleModel->insert([
                'customer_id'      => $customerId,
                'staff_id'         => session()->get('staff_id'),
                'total_price'      => $totalPrice,
                'payment_received' => $paymentReceived,
                'change_due'       => $paymentReceived - $totalPrice,
                'sale_date'        => date('Y-m-d H:i:s'),
            ]);

            // 4. Create Sale Items and update inventory
            foreach ($cart as $cartItem) {
                $saleItemModel->insert([
                    'sale_id'    => $saleId,
                    'item_id'    => $cartItem->id,
                    'quantity'   => $cartItem->quantity,
                    'item_price' => $cartItem->price,
                    'subtotal'   => $cartItem->price * $cartItem->quantity,
                ]);

                // Decrease the stock quantity
                $itemModel->set('item_quantity', 'item_quantity - ' . $cartItem->quantity, false)
                    ->where('item_id', $cartItem->id)
                    ->update();
            }

            $db->transComplete(); // Complete the transaction

            if ($db->transStatus() === false) {
                return $this->failServerError('Transaction failed. Please try again.');
            }

            // Return the final sale ID for receipt generation
            return $this->respondCreated(['success' => true, 'sale_id' => $saleId]);

        } catch (\Exception $e) {
            $db->transRollback(); // Rollback on error
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Displays the receipt for a specific sale.
     * This now uses the getSaleDetails() method from the SaleModel to ensure
     * that customer information is included in the data passed to the view.
     *
     * @param int $sale_id The ID of the sale.
     */
    public function receipt($sale_id)
    {
        $saleModel = new \App\Models\SaleModel();

        // Use the getSaleDetails method from the SaleModel.
        // This method performs a JOIN to fetch customer details along with the sale info.
        $saleDetails = $saleModel->getSaleDetails($sale_id);

        // Check if the sale was found
        if (empty($saleDetails)) {
            return redirect()->to('staff/dashboard')->with('error', 'Sale not found.');
        }

        // The view expects two variables: '$sale' and '$sale_items'.
        // We need to structure the data accordingly from what getSaleDetails returns.
        $data = [
            'sale' => $saleDetails, // This array now contains 'customer_name'
            'sale_items' => $saleDetails['items'] // The items are in a sub-array
        ];

        // Optional: Remove the 'items' sub-array from the main 'sale' array to keep it clean,
        // as we are already passing it separately.
        unset($data['sale']['items']);

        // Load the receipt view with the correctly structured data
        return view('receipt', $data);
    }
}
