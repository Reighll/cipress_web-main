<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class StaffAuthFilter implements FilterInterface
{
    /**
     * This method is called before a controller is executed.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the 'staff_id' is set in the session.
        if (!session()->get('staff_id')) {
            // If not logged in, redirect to the staff login page.
            return redirect()->to('/login/staff')->with('error', 'You must be logged in to view this page.');
        }
    }

    /**
     * This method is called after a controller is executed.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller runs.
    }
}
