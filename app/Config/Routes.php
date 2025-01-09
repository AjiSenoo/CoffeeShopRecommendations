<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::landingPage');
$routes->post('customer/authenticate', 'Customer::authenticate');
$routes->get('admin/login', 'Admin::login');
$routes->get('customer/login', 'Customer::login');
$routes->get('customer/register', 'Customer::register');
$routes->post('customer/store', 'Customer::store');
$routes->get('customer/dashboard', 'Customer::dashboard');


// ------ don't change this ------
// Routes.php
$routes->get('/branches', 'BranchController::index');
$routes->post('/recommend', 'BranchController::recommend');
// Admin routes
$routes->post('admin/manage-queue', 'AdminController::manageQueue');

// Customer routes
$routes->post('customer/add-queue', 'CustomerController::addToQueue');
// ------- until this line --------
