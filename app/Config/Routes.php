<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home Routes
$routes->get('/', 'Home::landingPage');

// Customer Routes
$routes->post('customer/authenticate', 'Customer::authenticate');
$routes->get('customer/login', 'Customer::login');
$routes->get('customer/register', 'Customer::register');
$routes->post('customer/store', 'Customer::store');
$routes->get('customer/dashboard', 'Customer::dashboard', ['filter' => 'auth']);
$routes->post('customer/add-queue', 'CustomerController::addToQueue', ['filter' => 'auth']);
$routes->post('/customer/submit-review', 'Customer::submitReview', ['filter' => 'auth']);

// Admin Routes
$routes->get('admin/login', 'Admin::login');
$routes->post('admin/authenticate', 'Admin::authenticate');
$routes->get('admin/logout', 'Admin::logout');
$routes->get('admin/dashboard', 'Admin::dashboard', ['filter' => 'auth']);
$routes->post('/admin/add-queue', 'Admin::addQueue', ['filter' => 'auth']);
$routes->post('/admin/subtract-queue', 'Admin::subtractQueue', ['filter' => 'auth']);

// Branch Routes
$routes->get('/branches', 'BranchController::index', ['filter' => 'auth']);
$routes->post('/recommend', 'BranchController::recommend', ['filter' => 'auth']);
$routes->get('/branch/reviews/(:num)', 'BranchController::showReviews/$1');

// Queue Routes
$routes->post('/add-to-queue', 'QueueController::addToQueue', ['filter' => 'auth']);
