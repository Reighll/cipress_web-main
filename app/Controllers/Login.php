<?php

namespace App\Controllers;

class Login extends BaseController
{
    /**
     * [UPDATED]
     * Displays the main login chooser page.
     * The redirection logic has been removed from this file as requested.
     *
     * @return string
     */
    public function index()
    {
        // This page will now always be visible, allowing users to choose their login type.
        return view('login');
    }
}
