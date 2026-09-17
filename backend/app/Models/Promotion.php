<?php

class Promotion
{
    public ?string $MaKM;
    public ?string $TenKM;
    public ?string $MoTa;

    public ?string $LoaiKM;

    public float $GiaTri;
    public ?float $GiaTriToiDa;
    public float $DonToiThieu;

    public ?string $PhamViApDung;

    public array $DanhMuc;

    public $NgayBatDau;
    public $NgayKetThuc;

    public int $SoLuong;

    public ?string $TrangThai;

    public function __construct(array $data = [])
    {
        $this->MaKM = $data['MaKM'] ?? null;
        $this->TenKM = $data['TenKM'] ?? null;
        $this->MoTa = $data['MoTa'] ?? null;

        $this->LoaiKM = $data['LoaiKM'] ?? null;

        $this->GiaTri = (float) ($data['GiaTri'] ?? 0);

        $this->GiaTriToiDa =
            isset($data['GiaTriToiDa']) &&
            $data['GiaTriToiDa'] !== ''
                ? (float) $data['GiaTriToiDa']
                : null;

        $this->DonToiThieu =
            (float) ($data['DonToiThieu'] ?? 0);

        $this->PhamViApDung =
            $data['PhamViApDung'] ?? 'Toàn bộ';

        $this->DanhMuc =
            $data['DanhMuc'] ?? [];

        $this->NgayBatDau =
            $data['NgayBatDau'] ?? null;

        $this->NgayKetThuc =
            $data['NgayKetThuc'] ?? null;

        $this->SoLuong =
            (int) ($data['SoLuong'] ?? 0);

        $this->TrangThai =
            $data['TrangThai'] ?? 'Đang hoạt động';
    }
}