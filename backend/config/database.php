<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

class Database
{
    private static ?Database $instance = null;

    private Client $client;
    private $database;

    private function __construct()
    {
        $uri = getenv('MONGODB_URI');

        if (!$uri) {
            throw new Exception('Chưa cấu hình MONGODB_URI');
        }

        $this->client = new Client($uri);

        $this->database = $this->client->selectDatabase(
            getenv('MONGODB_DATABASE') ?: 'QL_CakeShop'
        );
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