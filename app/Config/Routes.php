<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- GENERAL & PUBLIC ROUTES ---

// Main login chooser page
$routes->get('/', 'Login::index');

// Logout route
$routes->get('/logout', 'OwnerLogin::logout');

// Standalone Login Routes
$routes->get('/login/owner', 'OwnerLogin::index');
$routes->post('/login/owner', 'OwnerLogin::attemptLogin');
$routes->get('/login/staff', 'StaffLogin::index');
$routes->post('/login/staff', 'StaffLogin::attemptLogin');

// Registration Routes
$routes->get('/register', 'Register::index');
$routes->get('/register/owner', 'OwnerRegistration::index');
$routes->post('/register/owner', 'OwnerRegistration::store');
$routes->get('/register/staff', 'StaffRegistration::index');
$routes->post('/register/staff', 'StaffRegistration::store');


// --- OWNER ROUTES (PROTECTED) ---
// This group requires the user to be logged in as an owner.
$routes->group('owner', ['filter' => 'owner_auth'], function ($routes) {
    $routes->get('dashboard', 'OwnerDashboard::index');

    // Inventory Management
    $routes->get('inventory', 'Inventory::index');
    $routes->get('inventory/delete/(:num)', 'Inventory::deleteItem/$1');
    $routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
    $routes->post('inventory/update/(:num)', 'Inventory::update/$1');
    $routes->get('add-item', 'AddItem::index');
    $routes->post('add-item', 'AddItem::store');

    // Staff & Sales Management
    $routes->get('staff-management', 'StaffManagement::index');
    $routes->get('staff/delete/(:num)', 'StaffManagement::deleteStaff/$1');
    $routes->get('sales-report', 'SalesReport::index');
    $routes->get('receipt/(:num)', 'SalesReport::viewReceipt/$1');


    // Staff approval from dashboard
    $routes->get('staff/approve/(:num)', 'OwnerDashboard::approveStaff/$1');
    $routes->get('staff/decline/(:num)', 'OwnerDashboard::declineStaff/$1');
});


// --- STAFF ROUTES (PROTECTED) ---
// This group contains the fix for the form submission.
$routes->group('staff', ['filter' => 'staff_auth'], function ($routes) {
    // The main dashboard page for staff.
    $routes->get('dashboard', 'StaffDashboard::index');

    // [THIS IS THE FIX] This route handles the form POST from the staff dashboard.
    $routes->post('dashboard/process_sale', 'StaffDashboard::process_sale');

    // This ensures the receipt page is handled by the staff controller.
    $routes->get('receipt/(:num)', 'StaffDashboard::receipt/$1');

    // The staff logout route.
    $routes->get('logout', 'StaffLogin::logout');
});