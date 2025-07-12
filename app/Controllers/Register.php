<?php

namespace App\Controllers;

class Register extends BaseController
{
    /**
     * Displays the registration page.
     *
     * @return string
     */
    public function index(): string
    {
        return view('register');
    }
}
