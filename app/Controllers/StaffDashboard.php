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
     * This now fetches inventory data directly and passes it to the view.
     */
    public function index()
    {
        $itemModel = new ItemModel();
        // Fetch all items and pass them to the view.
        // The view will now be responsible for rendering the initial inventory.
        $data['items'] = $itemModel->findAll();

        return view('staff_dashboard', $data);
    }

    /**
     * API endpoint to process the checkout.
     * This function remains unchanged as it's still needed for handling the cart submission.
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

            // --- THIS IS THE FIX ---
            // Ensure a consistent JSON error response is sent.
            // The status code (e.g., 400 for Bad Request) is important for the front-end to know it's an error.
            return $this->respond([
                'success' => false,
                'message' => $e->getMessage()
            ], 400); // 400 Bad Request is a good generic client error status
        }
    }
}