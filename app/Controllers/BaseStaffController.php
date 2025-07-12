<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseStaffController
 *
 * Provides a convenient place for loading components and performing functions
 * that are needed by all staff-facing controllers.
 */
abstract class BaseStaffController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();

        // Check if the staff member is logged in.
        // If not, redirect them to the staff login page.
        if (! $session->get('is_staff_logged_in')) {
            service('response')->redirect('/login/staff')->send();
            exit; // Stop further execution
        }
    }
}
