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
    $routes->get('inventory/delete/(:num)', 'Inventory::deleteItem/$1');
    $routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
    $routes->post('inventory/update/(:num)', 'Inventory::update/$1');

    $routes->get('add-item', 'AddItem::index');
    $routes->get('add-item', 'AddItem::index');
    $routes->post('add-item', 'AddItem::store');

    $routes->get('staff-management', 'StaffManagement::index');
    $routes->get('sales-report', 'SalesReport::index');

    // Routes for handling staff approval on the dashboard
    $routes->get('staff/approve/(:num)', 'OwnerDashboard::approveStaff/$1');
    $routes->get('staff/decline/(:num)', 'OwnerDashboard::declineStaff/$1');

    // --- This is the new, simplified delete route ---
    $routes->get('staff/delete/(:num)', 'StaffManagement::deleteStaff/$1');
});


// --- STAFF ROUTES ---
$routes->get('/login/staff', 'StaffLogin::index');
$routes->post('/login/staff', 'StaffLogin::attemptLogin');

$routes->group('staff', ['filter' => 'staff_auth'], function ($routes) {
    $routes->get('dashboard', 'StaffDashboard::index');
});


// --- REGISTRATION ROUTES ---
$routes->get('/register', 'Register::index');
$routes->get('/register/owner', 'OwnerRegistration::index');
$routes->post('/register/owner', 'OwnerRegistration::store');
$routes->get('/register/staff', 'StaffRegistration::index');
$routes->post('/register/staff', 'StaffRegistration::store');