<?php

namespace App\Controllers;

use App\Models\OwnerModel;
use CodeIgniter\API\ResponseTrait;

class OwnerRegistration extends BaseController
{
    use ResponseTrait;

    /**
     * Displays the owner registration page.
     *
     * @return string
     */
    public function index(): string
    {
        $ownerModel = new OwnerModel();
        // Pass the owner count to the view so it knows whether to show the system key field.
        // Using countAll() is the correct method for a simple table count.
        $data['ownerCount'] = $ownerModel->countAll();

        return view('owner_registration', $data);
    }

    /**
     * Handles the owner registration form submission.
     */
    public function attemptRegister()
    {
        $ownerModel = new OwnerModel();

        // 1. Set up validation rules.
        $rules = [
            'firstname' => 'required|min_length[2]|max_length[50]',
            'lastname'  => 'required|min_length[2]|max_length[50]',
            'username'  => 'required|min_length[3]|max_length[50]|is_unique[owner.owner_username]',
            'password'  => 'required|min_length[8]|max_length[255]',
            'pass_confirm' => 'required|matches[password]',
        ];

        // 2. If owners already exist, the system key is required and must be valid.
        // We use countAll() again for a reliable count.
        if ($ownerModel->countAll() > 0) {
            $rules['system_key'] = 'required|is_existing_system_key';
        }

        // 3. Validate the input using the built-in helper.
        // This automatically uses the custom rule from app/Validation/OwnerRules.php
        if (!$this->validate($rules)) {
            // If validation fails, return the errors as JSON.
            return $this->fail($this->validator->getErrors());
        }

        // 4. Prepare the data to be saved.
        $data = [
            'owner_firstname' => $this->request->getVar('firstname'),
            'owner_lastname'  => $this->request->getVar('lastname'),
            'owner_username'  => $this->request->getVar('username'),
            'owner_password'  => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            // A new, unique key is generated for every new owner.
            'owner_systemkey' => uniqid('cipress_key_', true),
        ];

        // 5. Save the data to the database.
        if ($ownerModel->save($data) === false) {
            return $this->fail($ownerModel->errors());
        }

        // 6. Return a success response.
        return $this->respondCreated([
            'status'   => 201,
            'error'    => null,
            'messages' => [
                'success' => 'Owner account created successfully!'
            ]
        ]);
    }
}
