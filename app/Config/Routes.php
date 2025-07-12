<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// General and Login Routes
$routes->get('/', 'Login::index');
$routes->get('/logout', 'OwnerLogin::logout');

// --- OWNER ROUTES ---
$routes->get('/login/owner', 'OwnerLogin::index');
$routes->post('/login/owner', 'OwnerLogin::attemptLogin');

// Group for routes that require an owner to be logged in
$routes->group('owner', ['filter' => 'owner_auth'], function ($routes) {
    $routes->get('dashboard', 'OwnerDashboard::index');
    $routes->get('inventory', 'Inventory::index');
    $routes->get('add-item', 'AddItem::index');
    $routes->get('staff-management', 'StaffManagement::index');
    $routes->get('sales-report', 'SalesReport::index');

    // --- NEW: Routes for approving and declining staff ---
    $routes->get('staff/approve/(:num)', 'OwnerDashboard::approveStaff/$1');
    $routes->get('staff/decline/(:num)', 'OwnerDashboard::declineStaff/$1');
});


// --- STAFF ROUTES ---
$routes->get('/login/staff', 'StaffLogin::index');
$routes->post('/login/staff', 'StaffLogin::attemptLogin');
$routes->get('/staff/dashboard', 'StaffDashboard::index', ['filter' => 'staff_auth']);


// --- REGISTRATION ROUTES ---
$routes->get('/register', 'Register::index');
$routes->get('/register/owner', 'OwnerRegistration::index');
// Note: The method in your controller is 'store', not 'attemptRegister'
$routes->post('/register/owner', 'OwnerRegistration::store');
$routes->get('/register/staff', 'StaffRegistration::index');
$routes->post('/register/staff', 'StaffRegistration::store');

