<?php
declare(strict_types=1);
namespace Client\Services;

use Client\Repositories\ProductRepository;
use Shared\Helpers\DocumentSerializer;

final class ProductService
{
    public function list(): array
    {
        $repo = new ProductRepository();

        $items = $repo->paginate(
            ['TrangThai' => 'Đang bán'],
            1,
            100,
            ['NgayBan' => -1]
        );

        return array_map(
            fn ($item) => DocumentSerializer::document($item),
            $items
        );
    }

    public function show(string $code): array
    {
        $item = (new ProductRepository())->findByCode($code);

        if ($item === null) {
            throw new \RuntimeException('Không tìm thấy sản phẩm');
        }

        return DocumentSerializer::document($item);
    }
}
