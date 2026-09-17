<?php

require_once __DIR__
    . '/../../Services/PromotionService.php';

require_once __DIR__
    . '/../../Helpers/Response.php';

class PromotionController
{
    private PromotionService $service;

    public function __construct()
    {
        $this->service =
            new PromotionService();
    }

    public function index(): void
    {
        try {
            $page =
                max(
                    1,
                    (int) ($_GET['page'] ?? 1)
                );

            $limit =
                min(
                    100,
                    max(
                        1,
                        (int)
                        ($_GET['limit'] ?? 10)
                    )
                );

            $search =
                trim(
                    $_GET['search'] ?? ''
                );

            $status =
                trim(
                    $_GET['status'] ?? ''
                );

            $data =
                $this->service->getAll(
                    $page,
                    $limit,
                    $search,
                    $status
                );

            Response::success($data);

        } catch (Throwable $e) {
            Response::error(
                $e->getMessage(),
                500
            );
        }
    }

    public function show(
        string $maKM
    ): void {
        try {
            $data =
                $this->service
                    ->findByCode($maKM);

            Response::success($data);

        } catch (Throwable $e) {
            Response::error(
                $e->getMessage(),
                404
            );
        }
    }

    public function store(): void
    {
        try {
            $input =
                json_decode(
                    file_get_contents(
                        'php://input'
                    ),
                    true
                ) ?? [];

            $data =
                $this->service
                    ->create($input);

            Response::success(
                $data,
                'Thêm khuyến mãi thành công',
                201
            );

        } catch (
            InvalidArgumentException $e
        ) {
            Response::error(
                'Dữ liệu không hợp lệ',
                422,
                json_decode(
                    $e->getMessage(),
                    true
                )
            );

        } catch (Throwable $e) {
            Response::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function update(
        string $maKM
    ): void {
        try {
            $input =
                json_decode(
                    file_get_contents(
                        'php://input'
                    ),
                    true
                ) ?? [];

            $this->service->update(
                $maKM,
                $input
            );

            Response::success(
                null,
                'Cập nhật khuyến mãi thành công'
            );

        } catch (
            InvalidArgumentException $e
        ) {
            Response::error(
                'Dữ liệu không hợp lệ',
                422,
                json_decode(
                    $e->getMessage(),
                    true
                )
            );

        } catch (Throwable $e) {
            Response::error(
                $e->getMessage(),
                400
            );
        }
    }

    public function destroy(
        string $maKM
    ): void {
        try {
            $this->service
                ->delete($maKM);

            Response::success(
                null,
                'Xóa khuyến mãi thành công'
            );

        } catch (Throwable $e) {
            Response::error(
                $e->getMessage(),
                400
            );
        }
    }
}