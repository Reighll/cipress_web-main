<?php

namespace App\Controllers;

// Extend the new BaseOwnerController
class OwnerDashboard extends BaseOwnerController
{
    /**
     * Displays the owner dashboard.
     *
     * @return string
     */
    public function index(): string
    {
        return view('owner_dashboard');
    }
}
