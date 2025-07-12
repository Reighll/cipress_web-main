<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class OwnerAuthFilter implements FilterInterface
{
    /**
     * Checks if an owner is logged in before allowing access to a route.
     * If not logged in, they are redirected to the owner login page.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Use session()->has() for a clear and reliable check.
        if (!session()->has('owner_id')) {
            // Redirect to the correct owner login page.
            return redirect()->to('/login/owner')->with('error', 'You must be logged in as an owner to access this page.');
        }
    }

    /**
     * This method is called after a controller has executed.
     * No action is needed here.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here.
    }
}