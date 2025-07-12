<?php

namespace App\Controllers;

use App\Models\ItemModel;

class AddItem extends BaseOwnerController
{
    /**
     * Displays the "Add Item" form.
     */
    public function index()
    {
        // Pass the validation service to the view
        $data['validation'] = \Config\Services::validation();
        return view('add_item', $data);
    }

    /**
     * Handles the form submission to add a new item using a direct redirect method.
     */
    public function store()
    {
        // 1. Define validation rules.
        $rules = [
            'name'          => 'required|min_length[3]|max_length[255]',
            'category'      => 'required|max_length[255]',
            'quantity'      => 'required|numeric|greater_than_equal_to[0]',
            'initial_price' => 'required|numeric|greater_than_equal_to[0]',
        ];

        // 2. Run validation.
        if (!$this->validate($rules)) {
            // If validation fails, redirect back to the form with the errors.
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. If validation passes, save the data.
        $itemModel = new ItemModel();

        $data = [
            'item_name'          => $this->request->getPost('name'),
            'item_category'      => $this->request->getPost('category'),
            'item_quantity'      => $this->request->getPost('quantity'),
            'item_initial_price' => $this->request->getPost('initial_price'),
        ];

        if ($itemModel->save($data)) {
            // On success, redirect to the inventory with a success message.
            return redirect()->to('/owner/inventory')->with('success', 'Item added successfully!');
        } else {
            // If saving fails for some reason, redirect back with an error.
            return redirect()->back()->withInput()->with('error', 'Failed to save the item. Please try again.');
        }
    }
}