<?php
namespace App\Controllers;
class Inventory extends BaseOwnerController {
    public function index() { return view('inventory'); }
}