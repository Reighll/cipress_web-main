<?php

namespace App\Controllers;

use App\Models\ItemModel;

class Inventory extends BaseOwnerController
{
    /**
     * Displays the inventory page with a list of all items.
     */
    public function index()
    {
        $itemModel = new ItemModel();
        $data['items'] = $itemModel->findAll();
        return view('inventory', $data);
    }

    /**
     * Shows the form to edit a specific item.
     *
     * @param int $itemId The ID of the item to edit.
     */
    public function edit($itemId)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($itemId);

        // Redirect with an error if item is not found.
        if ($item === null) {
            return redirect()->to('/owner/inventory')->with('error', 'Item not found.');
        }

        $data['item'] = $item;
        return view('edit_item', $data);
    }

    /**
     * Processes the submission of the edit form and updates the item.
     *
     * @param int $itemId The ID of the item to update.
     */
    public function update($itemId)
    {
        $itemModel = new ItemModel();
        // Ensure the item exists before trying to update it.
        if ($itemModel->find($itemId) === null) {
            return redirect()->to('/owner/inventory')->with('error', 'Item not found.');
        }

        // 1. Define validation rules.
        $rules = [
            'name'          => 'required|min_length[3]|max_length[255]',
            'category'      => 'required|max_length[255]',
            'quantity'      => 'required|numeric|greater_than_equal_to[0]',
            'initial_price' => 'required|numeric|greater_than_equal_to[0]',
        ];

        // 2. Run validation.
        if (!$this->validate($rules)) {
            // If validation fails, redirect back to the edit form with errors.
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 3. Prepare the data for updating.
        $data = [
            'item_name'          => $this->request->getPost('name'),
            'item_category'      => $this->request->getPost('category'),
            'item_quantity'      => $this->request->getPost('quantity'),
            'item_initial_price' => $this->request->getPost('initial_price'),
        ];

        // 4. Attempt to update the data.
        if ($itemModel->update($itemId, $data)) {
            return redirect()->to('/owner/inventory')->with('success', 'Item updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update the item. Please try again.');
        }
    }


    /**
     * Deletes an item from the inventory.
     *
     * @param int $itemId The ID of the item to delete.
     */
    public function deleteItem($itemId)
    {
        $itemModel = new ItemModel();
        if ($itemModel->find($itemId) === null) {
            return redirect()->to('/owner/inventory')->with('error', 'Item not found.');
        }
        if ($itemModel->delete($itemId)) {
            return redirect()->to('/owner/inventory')->with('success', 'Item deleted successfully.');
        } else {
            return redirect()->to('/owner/inventory')->with('error', 'Could not delete the item.');
        }
    }
}