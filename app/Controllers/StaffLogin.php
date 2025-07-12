<?php
namespace App\Controllers;
use App\Models\StaffModel;
use CodeIgniter\API\ResponseTrait;

class StaffLogin extends BaseController {
    use ResponseTrait;

    public function index() {
        return view('staff_login');
    }

    public function attemptLogin() {
        $rules = ['username' => 'required', 'password' => 'required'];
        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $staffModel = new StaffModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $staff = $staffModel->where('staff_username', $username)->first();

        if ($staff === null || !password_verify($password, $staff['staff_password'])) {
            return $this->failUnauthorized('Invalid username or password.');
        }

        session()->set([
            'staff_id'       => $staff['staff_id'],
            'staff_username' => $staff['staff_username'],
            'is_staff_logged_in' => true,
        ]);

        return $this->respond(['status' => 200, 'messages' => ['success' => 'Login successful! Redirecting...']]);
    }
}