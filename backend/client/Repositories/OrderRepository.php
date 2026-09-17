<?php

declare(strict_types=1);

namespace Client\Repositories;

use Shared\Repositories\BaseRepository;

final class OrderRepository extends BaseRepository
{
    protected function collectionName(): string
    {
        return 'DonHangs';
    }

    protected function codeField(): string
    {
        return 'MaDH';
    }
}
