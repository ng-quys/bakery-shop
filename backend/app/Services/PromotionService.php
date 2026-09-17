<?php

require_once __DIR__
    . '/../Repositories/PromotionRepository.php';

require_once __DIR__
    . '/../Requests/PromotionRequest.php';

use MongoDB\BSON\UTCDateTime;

class PromotionService
{
    private PromotionRepository $repository;

    public function __construct()
    {
        $this->repository =
            new PromotionRepository();
    }

    public function getAll(
        int $page = 1,
        int $limit = 10,
        string $search = '',
        string $status = ''
    ): array {
        $filter = [];

        if ($search !== '') {
            $filter['$or'] = [
                [
                    'MaKM' => [
                        '$regex' => $search,
                        '$options' => 'i'
                    ]
                ],
                [
                    'TenKM' => [
                        '$regex' => $search,
                        '$options' => 'i'
                    ]
                ]
            ];
        }

        if ($status !== '') {
            $filter['TrangThai'] = $status;
        }

        $items = $this->repository->getAll(
            $filter,
            $page,
            $limit
        );

        $total =
            $this->repository->count($filter);

        return [
            'items' => array_map(
                [$this, 'formatPromotion'],
                $items
            ),

            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' =>
                    (int) ceil($total / $limit)
            ]
        ];
    }

    public function findByCode(
        string $maKM
    ): array {
        $promotion =
            $this->repository->findByCode($maKM);

        if (!$promotion) {
            throw new Exception(
                'Không tìm thấy khuyến mãi'
            );
        }

        return $this->formatPromotion(
            $promotion
        );
    }

    public function create(
        array $input
    ): array {
        PromotionRequest::validate($input);

        $maKM = strtoupper(
            trim($input['MaKM'])
        );

        if (
            $this->repository
                ->existsByCode($maKM)
        ) {
            throw new Exception(
                'Mã khuyến mãi đã tồn tại'
            );
        }

        $data =
            $this->prepareData($input);

        $data['MaKM'] = $maKM;

        $result =
            $this->repository->create($data);

        return [
            'id' =>
                (string) $result->getInsertedId(),

            'MaKM' => $maKM
        ];
    }

    public function update(
        string $maKM,
        array $input
    ): void {
        if (
            !$this->repository
                ->existsByCode($maKM)
        ) {
            throw new Exception(
                'Khuyến mãi không tồn tại'
            );
        }

        PromotionRequest::validate(
            $input,
            true
        );

        $data =
            $this->prepareData($input);

        unset($data['MaKM']);

        $this->repository->update(
            $maKM,
            $data
        );
    }

    public function delete(
        string $maKM
    ): void {
        if (
            !$this->repository
                ->existsByCode($maKM)
        ) {
            throw new Exception(
                'Khuyến mãi không tồn tại'
            );
        }

        $this->repository->delete($maKM);
    }

    private function prepareData(
        array $input
    ): array {
        $data = [
            'TenKM' =>
                trim($input['TenKM']),

            'MoTa' =>
                trim($input['MoTa'] ?? ''),

            'LoaiKM' =>
                $input['LoaiKM'],

            'GiaTri' =>
                (float) $input['GiaTri'],

            'GiaTriToiDa' =>
                isset($input['GiaTriToiDa']) &&
                $input['GiaTriToiDa'] !== ''
                    ? (float)
                        $input['GiaTriToiDa']
                    : null,

            'DonToiThieu' =>
                (float)
                ($input['DonToiThieu'] ?? 0),

            'PhamViApDung' =>
                $input['PhamViApDung']
                ?? 'Toàn bộ',

            'NgayBatDau' =>
                new UTCDateTime(
                    strtotime(
                        $input['NgayBatDau']
                    ) * 1000
                ),

            'NgayKetThuc' =>
                new UTCDateTime(
                    strtotime(
                        $input['NgayKetThuc']
                    ) * 1000
                ),

            'SoLuong' =>
                (int)
                ($input['SoLuong'] ?? 0),

            'TrangThai' =>
                $input['TrangThai']
                ?? 'Đang hoạt động'
        ];

        if (
            ($input['PhamViApDung'] ?? '') ===
            'Danh mục'
        ) {
            $data['DanhMuc'] =
                array_map(
                    function ($maDM) {
                        return [
                            'MaDM' => $maDM
                        ];
                    },
                    $input['DanhMuc'] ?? []
                );
        } else {
            $data['DanhMuc'] = [];
        }

        return $data;
    }

    private function formatPromotion(
        $item
    ): array {
        return [
            'id' =>
                isset($item['_id'])
                    ? (string) $item['_id']
                    : null,

            'MaKM' =>
                $item['MaKM'] ?? '',

            'TenKM' =>
                $item['TenKM'] ?? '',

            'MoTa' =>
                $item['MoTa'] ?? '',

            'LoaiKM' =>
                $item['LoaiKM'] ?? '',

            'GiaTri' =>
                $item['GiaTri'] ?? 0,

            'GiaTriToiDa' =>
                $item['GiaTriToiDa'] ?? null,

            'DonToiThieu' =>
                $item['DonToiThieu'] ?? 0,

            'PhamViApDung' =>
                $item['PhamViApDung'] ?? '',

            'DanhMuc' =>
                $item['DanhMuc'] ?? [],

            'NgayBatDau' =>
                isset($item['NgayBatDau'])
                    ? $item['NgayBatDau']
                        ->toDateTime()
                        ->format('Y-m-d\TH:i')
                    : null,

            'NgayKetThuc' =>
                isset($item['NgayKetThuc'])
                    ? $item['NgayKetThuc']
                        ->toDateTime()
                        ->format('Y-m-d\TH:i')
                    : null,

            'SoLuong' =>
                $item['SoLuong'] ?? 0,

            'TrangThai' =>
                $item['TrangThai'] ?? ''
        ];
    }
}