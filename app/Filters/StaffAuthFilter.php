<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class StaffAuthFilter implements FilterInterface
{
    /**
     * This method is called before the controller is executed.
     * It checks if a staff member is logged in. If not, it redirects
     * them to the staff login page.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the 'staff_id' is set in the session.
        if (!session()->has('staff_id')) {
            // --- THIS IS THE FIX ---
            // Redirect to the correct staff login page.
            return redirect()->to('/login/staff')->with('error', 'You must be logged in to view this page.');
        }
    }

    /**
     * This method is called after the controller is executed.
     * We don't need to do anything here for this filter.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed.
    }
}