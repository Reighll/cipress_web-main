<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class OwnerAuthFilter implements FilterInterface
{
    /**
     * This method is called before a controller is executed.
     * It checks if an owner is logged in.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the 'owner_id' key exists and is not empty in the session.
        if (!session()->has('owner_id') || empty(session()->get('owner_id'))) {
            // If the owner is not logged in, redirect them to the owner login page.
            return redirect()->to('/login/owner')->with('error', 'You must be logged in to view this page.');
        }
    }

    /**
     * This method is called after a controller is executed.
     * We don't need to do anything here for this filter.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed.
    }
}
