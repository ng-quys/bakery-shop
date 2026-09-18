<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;
use MongoDB\Database;

final class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;

    private Client $client;
    private Database $database;

    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();

        $uri = $_ENV['MONGODB_URI'] ?? '';
        $databaseName = $_ENV['MONGODB_DATABASE'] ?? 'QL_CakeShop';

        if ($uri === '') {
            throw new RuntimeException('Chưa cấu hình MONGODB_URI trong backend/.env');
        }

        $this->client = new Client($uri);
        $this->database = $this->client->selectDatabase($databaseName);
    }

    public static function getInstance(): DatabaseConnection
    {
        return self::$instance ??= new DatabaseConnection();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }
}
