<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseOwnerController
 *
 * Provides a convenient place for loading components and performing functions
 * that are needed by all owner-facing controllers.
 * Extends from this class for any new owner controllers.
 */
abstract class BaseOwnerController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();

        // Check if the owner is logged in.
        // If not, redirect them to the owner login page.
        if (! $session->get('is_owner_logged_in')) {
            // Using service('response') to perform the redirect.
            service('response')->redirect('/login/owner')->send();
            exit; // Stop further execution
        }
    }
}
