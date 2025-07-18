<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- GENERAL & PUBLIC ROUTES ---
$routes->get('/', 'Login::index');
$routes->get('/logout', 'OwnerLogin::logout');
$routes->get('/login/owner', 'OwnerLogin::index');
$routes->post('/login/owner', 'OwnerLogin::attemptLogin');
$routes->get('/login/staff', 'StaffLogin::index');
$routes->post('/login/staff', 'StaffLogin::attemptLogin');
$routes->get('/register', 'Register::index');
$routes->get('/register/owner', 'OwnerRegistration::index');
$routes->post('/register/owner', 'OwnerRegistration::store');
$routes->get('/register/staff', 'StaffRegistration::index');
$routes->post('/register/staff', 'StaffRegistration::store');


// --- OWNER ROUTES (PROTECTED) ---
$routes->group('owner', ['filter' => 'owner_auth'], function ($routes) {
    $routes->get('dashboard', 'OwnerDashboard::index');
    $routes->get('settings', 'OwnerSettings::index');
    $routes->post('settings/update-password', 'OwnerSettings::updatePassword');
    $routes->post('settings/update-username', 'OwnerSettings::updateUsername');
    $routes->get('inventory', 'Inventory::index');
    $routes->get('inventory/delete/(:num)', 'Inventory::deleteItem/$1');
    $routes->get('inventory/edit/(:num)', 'Inventory::edit/$1');
    $routes->post('inventory/update/(:num)', 'Inventory::update/$1');
    $routes->get('add-item', 'AddItem::index');
    $routes->post('add-item', 'AddItem::store');
    $routes->get('staff-management', 'StaffManagement::index');
    $routes->get('staff/delete/(:num)', 'StaffManagement::deleteStaff/$1');
    $routes->get('sales-report', 'SalesReport::index');
    $routes->get('receipt/(:num)', 'SalesReport::viewReceipt/$1');
    $routes->get('staff/approve/(:num)', 'OwnerDashboard::approveStaff/$1');
    $routes->get('staff/decline/(:num)', 'OwnerDashboard::declineStaff/$1');
});


// --- STAFF ROUTES (PROTECTED) ---
$routes->group('staff', ['filter' => 'staff_auth'], function ($routes) {
    $routes->get('dashboard', 'StaffDashboard::index');
    $routes->post('dashboard/process_sale', 'StaffDashboard::process_sale');
    $routes->get('receipt/(:num)', 'StaffDashboard::receipt/$1');
    $routes->get('logout', 'StaffLogin::logout');

    // Staff Settings Routes
    $routes->get('settings', 'StaffSettings::index');
    $routes->post('settings/update-password', 'StaffSettings::updatePassword');
    $routes->post('settings/update-username', 'StaffSettings::updateUsername');

    // Attendance Routes
    $routes->get('attendance', 'Attendance::index');
    $routes->post('attendance/clock-in', 'Attendance::clockIn');
    $routes->post('attendance/clock-out', 'Attendance::clockOut');
});