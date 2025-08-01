<?php

namespace App\Controllers;

use App\Models\StaffModel;
use CodeIgniter\API\ResponseTrait;

class StaffLogin extends BaseController
{
    use ResponseTrait;

    /**
     * Displays the staff login page or redirects if already logged in.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        // [THE FIX] If staff is already logged in, redirect to their dashboard
        if (session()->get('is_staff_logged_in')) {
            return redirect()->to('staff/dashboard');
        }

        return view('staff_login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $staffModel = new StaffModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $staff = $staffModel->where('staff_username', $username)->first();

        // 1. Check if user exists and password is correct.
        if ($staff === null || !password_verify($password, $staff['staff_password'])) {
            return $this->failUnauthorized('Invalid username or password.');
        }

        // 2. Check the staff member's status.
        if ($staff['staff_status'] === 'pending') {
            return $this->failUnauthorized('Your account is pending approval from an owner.');
        }

        if ($staff['staff_status'] === 'declined') {
            return $this->failUnauthorized('Your account registration has been declined.');
        }

        // 3. If status is 'approved', proceed with login.
        $session = session();
        $sessionData = [
            'staff_id'       => $staff['staff_id'],
            'staff_username' => $staff['staff_username'],
            'is_staff_logged_in' => true,
        ];
        $session->set($sessionData);

        return $this->respond([
            'status'   => 200,
            'messages' => ['success' => 'Login successful! Redirecting...']
        ]);
    }

    /**
     * Logs the staff member out.
     */
    public function logout()
    {
        session()->remove(['staff_id', 'staff_username', 'is_staff_logged_in']);
        return redirect()->to('/login/staff')->with('success', 'You have been logged out successfully.');
    }
}
