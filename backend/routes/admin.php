<?php

require_once __DIR__
    . '/../app/Controllers/Admin/PromotionController.php';

$controller =
    new PromotionController();

$method =
    $_SERVER['REQUEST_METHOD'];

$uri =
    parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

$uri =
    rtrim($uri, '/');

if (
    $uri ===
    '/api/admin/promotions'
) {
    switch ($method) {
        case 'GET':
            $controller->index();
            break;

        case 'POST':
            $controller->store();
            break;

        default:
            Response::error(
                'Method không được hỗ trợ',
                405
            );
    }
}

if (
    preg_match(
        '#^/api/admin/promotions/([^/]+)$#',
        $uri,
        $matches
    )
) {
    $maKM =
        urldecode($matches[1]);

    switch ($method) {
        case 'GET':
            $controller->show($maKM);
            break;

        case 'PUT':
            $controller->update($maKM);
            break;

        case 'DELETE':
            $controller->destroy($maKM);
            break;

        default:
            Response::error(
                'Method không được hỗ trợ',
                405
            );
    }
}