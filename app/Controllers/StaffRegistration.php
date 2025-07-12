<?php

namespace App\Controllers;

use App\Models\StaffModel;
use CodeIgniter\API\ResponseTrait;

class StaffRegistration extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return view('staff_registration');
    }

    /**
     * Handles the creation of a new staff account.
     */
    public function store()
    {
        $rules = [
            'firstname'    => 'required|min_length[2]|max_length[50]',
            'lastname'     => 'required|min_length[2]|max_length[50]',
            'username'     => 'required|min_length[3]|max_length[50]|is_unique[staff.staff_username]',
            'password'     => 'required|min_length[8]|max_length[255]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            // If validation fails, return the errors as JSON.
            return $this->fail($this->validator->getErrors());
        }

        $staffModel = new StaffModel();

        $data = [
            'staff_firstname' => $this->request->getVar('firstname'),
            'staff_lastname'  => $this->request->getVar('lastname'),
            'staff_username'  => $this->request->getVar('username'),
            'staff_password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            // --- THIS IS THE FIX ---
            // Set the default status for all new staff registrations.
            'staff_status'    => 'pending',
        ];

        if ($staffModel->save($data) === false) {
            // If the model fails to save for any reason, return its errors.
            return $this->fail($staffModel->errors());
        }

        // On success, return a success message.
        $response = [
            'status'   => 201,
            'messages' => [
                'success' => 'Account created! Please wait for an owner to approve your registration.'
            ]
        ];

        return $this->respondCreated($response);
    }
}
