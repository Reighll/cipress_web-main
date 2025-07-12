<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// General and Login Routes
$routes->get('/', 'Login::index');
$routes->get('/logout', 'OwnerLogin::logout'); // General logout can be handled by owner controller

// Owner Routes
$routes->get('/login/owner', 'OwnerLogin::index');
$routes->post('/login/owner', 'OwnerLogin::attemptLogin');
$routes->get('/owner/dashboard', 'OwnerDashboard::index');
$routes->get('/owner/inventory', 'Inventory::index');
$routes->get('/owner/add-item', 'AddItem::index');
$routes->get('/owner/staff-management', 'StaffManagement::index');
$routes->get('/owner/sales-report', 'SalesReport::index');

// Staff Routes
$routes->get('/login/staff', 'StaffLogin::index');
$routes->post('/login/staff', 'StaffLogin::attemptLogin');
$routes->get('/staff/dashboard', 'StaffDashboard::index');

// Registration Routes
$routes->get('/register', 'Register::index');
$routes->get('/register/owner', 'OwnerRegistration::index');
$routes->post('/register/owner', 'OwnerRegistration::attemptRegister');
$routes->get('/register/staff', 'StaffRegistration::index');
$routes->post('/register/staff', 'StaffRegistration::attemptRegister');
