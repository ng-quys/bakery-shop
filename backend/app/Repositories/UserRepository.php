<?php

require_once __DIR__ . '/../../config/database.php';

use MongoDB\BSON\UTCDateTime;

class UserRepository
{
    private $collection;

    public function __construct()
    {
        $database = Database::getDatabase();

        $this->collection = $database->selectCollection('users');
    }


    public function findByEmail(string $email)
    {
        return $this->collection->findOne([
            'email' => strtolower(trim($email))
        ]);
    }


    public function findByGoogleId(string $googleId)
    {
        return $this->collection->findOne([
            'googleId' => $googleId
        ]);
    }


    public function findByResetToken(string $tokenHash)
    {
        return $this->collection->findOne([
            'resetPasswordToken' => $tokenHash,
            'resetPasswordExpiresAt' => [
                '$gt' => new UTCDateTime()
            ]
        ]);
    }


    public function create(array $user)
    {
        $result = $this->collection->insertOne($user);

        return (string) $result->getInsertedId();
    }


    public function setResetPasswordToken(
        $userId,
        string $tokenHash,
        UTCDateTime $expiresAt
    ): void {
        $this->collection->updateOne(
            [
                '_id' => $userId
            ],
            [
                '$set' => [
                    'resetPasswordToken' => $tokenHash,
                    'resetPasswordExpiresAt' => $expiresAt,
                    'updatedAt' => new UTCDateTime()
                ]
            ]
        );
    }


    public function updatePassword(
        $userId,
        string $hashedPassword
    ): void {
        $this->collection->updateOne(
            [
                '_id' => $userId
            ],
            [
                '$set' => [
                    'password' => $hashedPassword,
                    'updatedAt' => new UTCDateTime()
                ],

                '$unset' => [
                    'resetPasswordToken' => '',
                    'resetPasswordExpiresAt' => ''
                ]
            ]
        );
    }


    public function attachGoogleAccount(
        $userId,
        string $googleId,
        ?string $avatar = null
    ): void {
        $update = [
            'googleId' => $googleId,
            'updatedAt' => new UTCDateTime()
        ];

        if ($avatar) {
            $update['avatar'] = $avatar;
        }

        $this->collection->updateOne(
            [
                '_id' => $userId
            ],
            [
                '$set' => $update
            ]
        );
    }
}