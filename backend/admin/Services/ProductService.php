<?php
declare(strict_types=1);
namespace Admin\Services;

use Admin\Repositories\ProductRepository;
use Admin\Requests\ProductRequest;
use Shared\Helpers\MongoValue;

final class ProductService extends BaseCrudService
{
    public function __construct()
    {
        parent::__construct(new ProductRepository());
    }

    protected function codeField(): string { return 'MaSP'; }

    protected function validate(array $data, bool $isUpdate = false): array
    {
        return ProductRequest::validate($data, $isUpdate);
    }

    protected function sort(): array
    {
        return ['NgayBan' => -1];
    }

    protected function buildFilter(string $search, string $status): array
    {
        $filter = $this->regexFilter($search, ['MaSP', 'TenSP', 'MaDM']);

        if ($status !== '') {
            $filter['TrangThai'] = $status;
        }

        return $filter;
    }

    protected function prepare(array $data, bool $isUpdate = false): array
    {
        if (isset($data['SoLuong'])) {
            $data['SoLuong'] = (int) $data['SoLuong'];
        }

        if (isset($data['NgayBan'])) {
            $data['NgayBan'] = MongoValue::date($data['NgayBan']);
        } elseif (!$isUpdate) {
            $data['NgayBan'] = new \MongoDB\BSON\UTCDateTime();
        }

        if (isset($data['KichThuoc']) && is_array($data['KichThuoc'])) {
            $data['KichThuoc'] = array_map(
                fn ($item) => [
                    'Ten' => (string) ($item['Ten'] ?? ''),
                    'Gia' => (float) ($item['Gia'] ?? 0)
                ],
                $data['KichThuoc']
            );
        }

        return $data;
    }
}
