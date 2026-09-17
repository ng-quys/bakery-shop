<?php
declare(strict_types=1);

use Admin\Controllers\CartController;
use Admin\Controllers\CategoryController;
use Admin\Controllers\CustomerController;
use Admin\Controllers\DashboardController;
use Admin\Controllers\EmployeeController;
use Admin\Controllers\FavoriteController;
use Admin\Controllers\OrderController;
use Admin\Controllers\ProductController;
use Admin\Controllers\PromotionController;

$router->get(
    '/api/admin/dashboard',
    [DashboardController::class, 'index']
);
$router->get(
    '/api/admin/dashboard/revenue',
    [
        DashboardController::class,
        'revenue'
    ]
);

$modules = [
    '/api/admin/products' => ProductController::class,
    '/api/admin/categories' => CategoryController::class,
    '/api/admin/orders' => OrderController::class,
    '/api/admin/customers' => CustomerController::class,
    '/api/admin/employees' => EmployeeController::class,
    '/api/admin/promotions' => PromotionController::class,
    '/api/admin/carts' => CartController::class,
    '/api/admin/favorites' => FavoriteController::class
];

foreach ($modules as $base => $controller) {
    $router->get($base, [$controller, 'index']);
    $router->get($base . '/{code}', [$controller, 'show']);
    $router->post($base, [$controller, 'store']);
    $router->put($base . '/{code}', [$controller, 'update']);
    $router->delete($base . '/{code}', [$controller, 'destroy']);
}
