<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use Dotenv\Dotenv;

class Database
{
    private static ?Database $instance = null;

    private Client $client;
    private $database;

    private function __construct()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();

        $uri = $_ENV['MONGODB_URI'] ?? null;

        if (!$uri) {
            throw new Exception(
                'Chưa cấu hình MONGODB_URI trong backend/.env'
            );
        }

        $databaseName =
            $_ENV['MONGODB_DATABASE'] ?? 'QL_CakeShop';

        $this->client = new Client($uri);

        $this->database =
            $this->client->selectDatabase($databaseName);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getDatabase()
    {
        return $this->database;
    }
}