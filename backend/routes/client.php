<?php
declare(strict_types=1);

use Client\Controllers\CategoryController;
use Client\Controllers\ProductController;
use Client\Controllers\PromotionController;

$router->get(
    '/api/products',
    [ProductController::class, 'index']
);

$router->get(
    '/api/products/{code}',
    [ProductController::class, 'show']
);

$router->get(
    '/api/categories',
    [CategoryController::class, 'index']
);

$router->get(
    '/api/promotions',
    [PromotionController::class, 'index']
);

// TODO sau:
// POST /api/auth/login
// POST /api/auth/register
// GET/POST/PUT/DELETE /api/cart
// POST /api/orders
// GET /api/orders/my-orders
// GET/POST/DELETE /api/favorites
