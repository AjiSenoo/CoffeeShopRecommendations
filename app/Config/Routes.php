<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Routes.php
$routes->get('/branches', 'BranchController::index');
$routes->post('/recommend', 'BranchController::recommend');
// Admin routes
$routes->post('admin/manage-queue', 'AdminController::manageQueue');

// Customer routes
$routes->post('customer/add-queue', 'CustomerController::addToQueue');

