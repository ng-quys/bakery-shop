<?php

require_once __DIR__ . '/../../config/database.php';

class PromotionRepository
{
    private $collection;

    public function __construct()
    {
        $database = Database::getInstance()->getDatabase();

        $this->collection =
            $database->selectCollection('KhuyenMais');
    }

    public function getAll(
        array $filter = [],
        int $page = 1,
        int $limit = 10
    ): array {
        $skip = ($page - 1) * $limit;

        return $this->collection
            ->find(
                $filter,
                [
                    'sort' => ['NgayBatDau' => -1],
                    'skip' => $skip,
                    'limit' => $limit
                ]
            )
            ->toArray();
    }

    public function count(array $filter = []): int
    {
        return $this->collection->countDocuments($filter);
    }

    public function findByCode(string $maKM)
    {
        return $this->collection->findOne([
            'MaKM' => $maKM
        ]);
    }

    public function existsByCode(string $maKM): bool
    {
        return $this->findByCode($maKM) !== null;
    }

    public function create(array $data)
    {
        return $this->collection->insertOne($data);
    }

    public function update(
        string $maKM,
        array $data
    ) {
        return $this->collection->updateOne(
            ['MaKM' => $maKM],
            [
                '$set' => $data
            ]
        );
    }

    public function delete(string $maKM)
    {
        return $this->collection->deleteOne([
            'MaKM' => $maKM
        ]);
    }
}