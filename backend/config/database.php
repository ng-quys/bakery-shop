<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use Dotenv\Dotenv;

class Database
{
    private static ?Client $client = null;

    public static function getDatabase()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->safeLoad();

        $uri = $_ENV['MONGODB_URI'] ?? null;
        $databaseName = $_ENV['MONGODB_DATABASE'] ?? null;

        if (!$uri || !$databaseName) {
            throw new Exception(
                'MongoDB configuration is missing.'
            );
        }

        if (self::$client === null) {
            self::$client = new Client($uri);
        }

        return self::$client->selectDatabase($databaseName);
    }
}