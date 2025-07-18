<?php

namespace App\Controllers;

use App\Models\OwnerModel;
use CodeIgniter\API\ResponseTrait;

class OwnerLogin extends BaseController
{
    use ResponseTrait;

    /**
     * Displays the owner login page or redirects if already logged in.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        // If owner is already logged in, redirect to their dashboard
        if (session()->get('is_owner_logged_in')) {
            return redirect()->to('owner/dashboard');
        }

        return view('owner_login');
    }

    /**
     * Attempts to log in an owner.
     */
    public function attemptLogin()
    {
        // 1. Set up validation rules.
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        // 2. Find the user by username.
        $ownerModel = new OwnerModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $owner = $ownerModel->where('owner_username', $username)->first();

        // 3. Check if user exists and password is correct.
        if ($owner === null || !password_verify($password, $owner['owner_password'])) {
            return $this->failUnauthorized('Invalid username or password.');
        }

        // 4. Set up the session for the logged-in owner.
        $session = session();
        $sessionData = [
            'owner_id'       => $owner['owner_id'],
            'owner_username' => $owner['owner_username'],
            'is_owner_logged_in' => true,
        ];
        $session->set($sessionData);

        // 5. Return a success response.
        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Login successful! Redirecting...'
            ]
        ]);
    }

    /**
     * Logs the owner out.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login/owner');
    }
}
