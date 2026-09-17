<?php

class PromotionRequest
{
    public static function validate(
        array $data,
        bool $isUpdate = false
    ): array {
        $errors = [];

        if (
            !$isUpdate &&
            empty(trim($data['MaKM'] ?? ''))
        ) {
            $errors['MaKM'] =
                'Mã khuyến mãi không được để trống';
        }

        if (empty(trim($data['TenKM'] ?? ''))) {
            $errors['TenKM'] =
                'Tên khuyến mãi không được để trống';
        }

        $allowedTypes = [
            'Phần trăm',
            'Tiền'
        ];

        if (
            empty($data['LoaiKM']) ||
            !in_array(
                $data['LoaiKM'],
                $allowedTypes,
                true
            )
        ) {
            $errors['LoaiKM'] =
                'Loại khuyến mãi không hợp lệ';
        }

        if (
            !isset($data['GiaTri']) ||
            !is_numeric($data['GiaTri']) ||
            $data['GiaTri'] <= 0
        ) {
            $errors['GiaTri'] =
                'Giá trị khuyến mãi phải lớn hơn 0';
        }

        if (
            ($data['LoaiKM'] ?? '') === 'Phần trăm' &&
            ($data['GiaTri'] ?? 0) > 100
        ) {
            $errors['GiaTri'] =
                'Khuyến mãi phần trăm không được vượt quá 100%';
        }

        if (
            isset($data['DonToiThieu']) &&
            $data['DonToiThieu'] < 0
        ) {
            $errors['DonToiThieu'] =
                'Đơn tối thiểu không hợp lệ';
        }

        if (empty($data['NgayBatDau'])) {
            $errors['NgayBatDau'] =
                'Ngày bắt đầu không được để trống';
        }

        if (empty($data['NgayKetThuc'])) {
            $errors['NgayKetThuc'] =
                'Ngày kết thúc không được để trống';
        }

        if (
            !empty($data['NgayBatDau']) &&
            !empty($data['NgayKetThuc']) &&
            strtotime($data['NgayKetThuc']) <=
            strtotime($data['NgayBatDau'])
        ) {
            $errors['NgayKetThuc'] =
                'Ngày kết thúc phải sau ngày bắt đầu';
        }

        if (
            isset($data['SoLuong']) &&
            (!is_numeric($data['SoLuong']) ||
            $data['SoLuong'] < 0)
        ) {
            $errors['SoLuong'] =
                'Số lượng không hợp lệ';
        }

        $allowedScopes = [
            'Toàn bộ',
            'Danh mục'
        ];

        if (
            !empty($data['PhamViApDung']) &&
            !in_array(
                $data['PhamViApDung'],
                $allowedScopes,
                true
            )
        ) {
            $errors['PhamViApDung'] =
                'Phạm vi áp dụng không hợp lệ';
        }

        if (
            ($data['PhamViApDung'] ?? '') ===
            'Danh mục' &&
            empty($data['DanhMuc'])
        ) {
            $errors['DanhMuc'] =
                'Hãy chọn ít nhất một danh mục';
        }

        if (!empty($errors)) {
            throw new InvalidArgumentException(
                json_encode(
                    $errors,
                    JSON_UNESCAPED_UNICODE
                )
            );
        }

        return $data;
    }
}