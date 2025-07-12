<?php

namespace App\Controllers;

// It's crucial that this extends BaseStaffController.php, not BaseController.
class StaffDashboard extends BaseStaffController
{
    /**
     * Displays the staff dashboard page.
     * The security check is now handled automatically by BaseStaffController.php.
     *
     * @return string
     */
    public function index(): string
    {
        return view('staff_dashboard');
    }
}
