<?php

namespace App\Controllers;

class Login extends BaseController
{
    /**
     * Displays the login page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('login');
    }
}
