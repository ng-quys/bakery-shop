<?php

require_once __DIR__ . '/../app/Requests/AuthRequest.php';
require_once __DIR__ . '/../app/Services/AuthService.php';

$authService = new AuthService();

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$body = json_decode(
    file_get_contents('php://input'),
    true
) ?? [];


header('Content-Type: application/json; charset=UTF-8');


try {

    // REGISTER
    if (
        $method === 'POST' &&
        str_ends_with($path, '/auth/register')
    ) {

        $data = AuthRequest::validateRegister($body);

        $user = $authService->register($data);

        http_response_code(201);

        echo json_encode([
            'success' => true,
            'message' => 'Đăng ký thành công.',
            'user' => $user
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // LOGIN
    if (
        $method === 'POST' &&
        str_ends_with($path, '/auth/login')
    ) {

        $data = AuthRequest::validateLogin($body);

        $user = $authService->login($data);

        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'user' => $user
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // GOOGLE LOGIN / REGISTER
    if (
        $method === 'POST' &&
        str_ends_with($path, '/auth/google')
    ) {

        $data = AuthRequest::validateGoogle($body);

        $user = $authService->googleLogin(
            $data['credential']
        );

        echo json_encode([
            'success' => true,
            'message' => 'Đăng nhập Google thành công.',
            'user' => $user
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // FORGOT PASSWORD
    if (
        $method === 'POST' &&
        str_ends_with($path, '/auth/forgot-password')
    ) {

        $data = AuthRequest::validateForgotPassword(
            $body
        );

        $authService->forgotPassword(
            $data['email']
        );


        echo json_encode([
            'success' => true,
            'message' =>
                'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi liên kết đặt lại mật khẩu.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    // RESET PASSWORD
    if (
        $method === 'POST' &&
        str_ends_with($path, '/auth/reset-password')
    ) {

        $data = AuthRequest::validateResetPassword(
            $body
        );


        $authService->resetPassword(
            $data['token'],
            $data['password']
        );


        echo json_encode([
            'success' => true,
            'message' =>
                'Đổi mật khẩu thành công. Bạn có thể đăng nhập bằng mật khẩu mới.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'API không tồn tại.'
    ], JSON_UNESCAPED_UNICODE);


} catch (Throwable $e) {

    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}