<?php

namespace App\Controllers;

use App\Models\OwnerModel;

class OwnerSettings extends BaseOwnerController
{
    /**
     * Displays the owner's account settings page.
     */
    public function index()
    {
        return view('owner_settings');
    }

    /**
     * Handles the form submission for updating the owner's password.
     */
    public function updatePassword()
    {
        // 1. Define validation rules for the password form.
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, redirect back with the errors.
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Get the current owner's data from the database.
        $ownerModel = new OwnerModel();
        $ownerId = session()->get('owner_id');
        $owner = $ownerModel->find($ownerId);

        // 3. Verify that the "current password" provided is correct.
        if (!password_verify($this->request->getPost('current_password'), $owner['owner_password'])) {
            return redirect()->back()->with('error', 'The current password you entered is incorrect.');
        }

        // 4. If everything is correct, hash the new password.
        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        // 5. Save the new password to the database.
        if ($ownerModel->update($ownerId, ['owner_password' => $newPasswordHash])) {
            // On success, redirect back with a success message.
            return redirect()->to('owner/settings')->with('success', 'Password updated successfully!');
        } else {
            // If saving fails, redirect back with a generic error.
            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * [THIS IS THE MISSING METHOD]
     * Handles the form submission for updating the owner's username.
     */
    public function updateUsername()
    {
        // 1. Define validation rules.
        $rules = [
            // Ensure the new username is not already taken by another owner.
            'new_username' => 'required|min_length[3]|is_unique[owner.owner_username]',
            'password'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Get owner data and verify password.
        $ownerModel = new OwnerModel();
        $ownerId = session()->get('owner_id');
        $owner = $ownerModel->find($ownerId);

        if (!password_verify($this->request->getPost('password'), $owner['owner_password'])) {
            return redirect()->back()->with('error', 'The password you entered is incorrect.');
        }

        // 3. Update the username in the database.
        $newUsername = $this->request->getPost('new_username');
        if ($ownerModel->update($ownerId, ['owner_username' => $newUsername])) {
            // 4. Update the username in the session so it displays correctly everywhere.
            session()->set('owner_username', $newUsername);
            return redirect()->to('owner/settings')->with('success', 'Username updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update username. Please try again.');
        }
    }
}