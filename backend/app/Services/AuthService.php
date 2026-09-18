<?php

require_once __DIR__ . '/../Repositories/UserRepository.php';
require_once __DIR__ . '/MailService.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\UTCDateTime;

class AuthService
{
    private UserRepository $userRepository;
    private MailService $mailService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->mailService = new MailService();
    }


    public function register(array $data): array
    {
        $existingUser = $this->userRepository->findByEmail(
            $data['email']
        );

        if ($existingUser) {
            throw new Exception(
                'Email này đã được đăng ký.'
            );
        }

        $user = [
            'fullName' => $data['fullName'],
            'email' => $data['email'],

            'password' => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),

            'provider' => 'local',

            'role' => 'customer',
            'status' => 'active',

            'createdAt' => new UTCDateTime(),
            'updatedAt' => new UTCDateTime()
        ];

        $id = $this->userRepository->create($user);

        return [
            'id' => $id,
            'fullName' => $user['fullName'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }


    public function login(array $data): array
    {
        $user = $this->userRepository->findByEmail(
            $data['email']
        );

        if (
            !$user ||
            !isset($user['password']) ||
            !password_verify(
                $data['password'],
                $user['password']
            )
        ) {
            throw new Exception(
                'Email hoặc mật khẩu không chính xác.'
            );
        }

        if (($user['status'] ?? 'active') !== 'active') {
            throw new Exception(
                'Tài khoản của bạn hiện không hoạt động.'
            );
        }

        return $this->formatUser($user);
    }


    public function googleLogin(string $credential): array
    {
        $clientId = $_ENV['GOOGLE_CLIENT_ID']
            ?? getenv('GOOGLE_CLIENT_ID');

        if (!$clientId) {
            throw new Exception(
                'Google Client ID chưa được cấu hình.'
            );
        }


        $client = new Google\Client([
            'client_id' => $clientId
        ]);


        $payload = $client->verifyIdToken(
            $credential
        );


        if (!$payload) {
            throw new Exception(
                'Đăng nhập Google không hợp lệ.'
            );
        }


        if (
            empty($payload['email']) ||
            empty($payload['sub'])
        ) {
            throw new Exception(
                'Google không cung cấp đủ thông tin tài khoản.'
            );
        }


        if (
            isset($payload['email_verified']) &&
            !$payload['email_verified']
        ) {
            throw new Exception(
                'Email Google chưa được xác minh.'
            );
        }


        $email = strtolower(
            trim($payload['email'])
        );

        $googleId = (string) $payload['sub'];

        $fullName = trim(
            $payload['name'] ?? 'Khách hàng'
        );

        $avatar = $payload['picture'] ?? null;


        $user = $this->userRepository->findByEmail(
            $email
        );


        /*
         * Email đã tồn tại.
         * Cho phép account local liên kết thêm Google.
         */
        if ($user) {

            if (($user['status'] ?? 'active') !== 'active') {
                throw new Exception(
                    'Tài khoản của bạn hiện không hoạt động.'
                );
            }


            if (
                empty($user['googleId']) ||
                (string) $user['googleId'] !== $googleId
            ) {
                $this->userRepository->attachGoogleAccount(
                    $user['_id'],
                    $googleId,
                    $avatar
                );
            }


            return [
                'id' => (string) $user['_id'],
                'fullName' => $user['fullName'],
                'email' => $user['email'],
                'role' => $user['role'] ?? 'customer',
                'avatar' => $avatar
            ];
        }


        /*
         * Chưa có account -> tạo mới.
         */
        $newUser = [
            'fullName' => $fullName,
            'email' => $email,

            'provider' => 'google',
            'googleId' => $googleId,

            'role' => 'customer',
            'status' => 'active',

            'createdAt' => new UTCDateTime(),
            'updatedAt' => new UTCDateTime()
        ];


        if ($avatar) {
            $newUser['avatar'] = $avatar;
        }


        $id = $this->userRepository->create(
            $newUser
        );


        return [
            'id' => $id,
            'fullName' => $fullName,
            'email' => $email,
            'role' => 'customer',
            'avatar' => $avatar
        ];
    }


    public function forgotPassword(string $email): void
    {
        $user = $this->userRepository->findByEmail(
            $email
        );


        /*
         * Không báo email có tồn tại hay không.
         * Tránh dò danh sách tài khoản.
         */
        if (!$user) {
            return;
        }


        $token = bin2hex(
            random_bytes(32)
        );


        /*
         * DB chỉ lưu hash.
         * Token thật chỉ gửi qua email.
         */
        $tokenHash = hash(
            'sha256',
            $token
        );


        $expiresAt = new UTCDateTime(
            (time() + 15 * 60) * 1000
        );


        $this->userRepository->setResetPasswordToken(
            $user['_id'],
            $tokenHash,
            $expiresAt
        );


        $frontendUrl =
            $_ENV['FRONTEND_URL']
            ?? getenv('FRONTEND_URL')
            ?: 'http://localhost:8080/bakery-shop/frontend';


        $resetUrl =
            rtrim($frontendUrl, '/') .
            '/page/auth/reset-password.html?token=' .
            urlencode($token);


        $this->mailService->sendResetPasswordEmail(
            $user['email'],
            $user['fullName'] ?? 'Khách hàng',
            $resetUrl
        );
    }


    public function resetPassword(
        string $token,
        string $password
    ): void {

        $tokenHash = hash(
            'sha256',
            $token
        );


        $user = $this->userRepository->findByResetToken(
            $tokenHash
        );


        if (!$user) {
            throw new Exception(
                'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'
            );
        }


        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        $this->userRepository->updatePassword(
            $user['_id'],
            $hashedPassword
        );
    }


    private function formatUser($user): array
    {
        return [
            'id' => (string) $user['_id'],
            'fullName' => $user['fullName'],
            'email' => $user['email'],
            'role' => $user['role'] ?? 'customer',
            'avatar' => $user['avatar'] ?? null
        ];
    }
}