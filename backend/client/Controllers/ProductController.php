<?php
declare(strict_types=1);
namespace Client\Controllers;

use Client\Services\ProductService;
use Shared\Helpers\Response;

final class ProductController
{
    public function index(): never
    {
        Response::success((new ProductService())->list());
    }

    public function show(string $code): never
    {
        try {
            Response::success((new ProductService())->show($code));
        } catch (\Throwable $e) {
            Response::error($e->getMessage(), 404);
        }
    }
}
