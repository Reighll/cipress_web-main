<?php

namespace App\Controllers;

use App\Models\StaffModel;

class StaffSettings extends BaseStaffController
{
    /**
     * Displays the staff member's account settings page.
     */
    public function index()
    {
        return view('staff_settings');
    }

    /**
     * Handles the form submission for updating the staff member's password.
     */
    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $staffModel = new StaffModel();
        $staffId = session()->get('staff_id');
        $staff = $staffModel->find($staffId);

        if (!password_verify($this->request->getPost('current_password'), $staff['staff_password'])) {
            return redirect()->back()->with('error', 'The current password you entered is incorrect.');
        }

        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        if ($staffModel->update($staffId, ['staff_password' => $newPasswordHash])) {
            return redirect()->to('staff/settings')->with('success', 'Password updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update password. Please try again.');
        }
    }

    /**
     * Handles the form submission for updating the staff member's username.
     */
    public function updateUsername()
    {
        $rules = [
            'new_username' => 'required|min_length[3]|is_unique[staff.staff_username]',
            'password'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $staffModel = new StaffModel();
        $staffId = session()->get('staff_id');
        $staff = $staffModel->find($staffId);

        if (!password_verify($this->request->getPost('password'), $staff['staff_password'])) {
            return redirect()->back()->with('error', 'The password you entered is incorrect.');
        }

        $newUsername = $this->request->getPost('new_username');
        if ($staffModel->update($staffId, ['staff_username' => $newUsername])) {
            // Update the username in the session
            session()->set('staff_username', $newUsername);
            return redirect()->to('staff/settings')->with('success', 'Username updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update username. Please try again.');
        }
    }
}