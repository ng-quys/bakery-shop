<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class MailService
{
    public function sendResetPasswordEmail(
        string $email,
        string $fullName,
        string $resetUrl
    ): void {
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();

            $mail->Host = $_ENV['MAIL_HOST']
                ?? getenv('MAIL_HOST')
                ?: 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL_USERNAME']
                ?? getenv('MAIL_USERNAME');

            $mail->Password = $_ENV['MAIL_PASSWORD']
                ?? getenv('MAIL_PASSWORD');

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = (int) (
                $_ENV['MAIL_PORT']
                ?? getenv('MAIL_PORT')
                ?: 587
            );

            $fromAddress = $_ENV['MAIL_FROM_ADDRESS']
                ?? getenv('MAIL_FROM_ADDRESS');

            $fromName = $_ENV['MAIL_FROM_NAME']
                ?? getenv('MAIL_FROM_NAME')
                ?: 'The Little Prince Pâtisserie';

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(
                $fromAddress,
                $fromName
            );

            $mail->addAddress(
                $email,
                $fullName
            );

            $mail->isHTML(true);

            $mail->Subject =
                'Đặt lại mật khẩu | The Little Prince Pâtisserie';


            $safeName = htmlspecialchars(
                $fullName,
                ENT_QUOTES,
                'UTF-8'
            );

            $safeUrl = htmlspecialchars(
                $resetUrl,
                ENT_QUOTES,
                'UTF-8'
            );


            $mail->Body = <<<HTML

<div style="
    font-family:Arial,sans-serif;
    max-width:600px;
    margin:auto;
    padding:40px;
    color:#453b3b;
">

    <h2 style="
        font-family:Georgia,serif;
        font-weight:normal;
        color:#6f5558;
    ">
        The Little Prince Pâtisserie
    </h2>

    <p>Xin chào {$safeName},</p>

    <p>
        Chúng tôi nhận được yêu cầu đặt lại mật khẩu
        cho tài khoản của bạn.
    </p>

    <p>
        Nhấn vào nút bên dưới để tạo mật khẩu mới.
    </p>

    <p style="margin:35px 0;">

        <a
            href="{$safeUrl}"
            style="
                display:inline-block;
                background:#8d6f73;
                color:#ffffff;
                padding:14px 26px;
                text-decoration:none;
                border-radius:6px;
            "
        >
            Đặt lại mật khẩu
        </a>

    </p>

    <p>
        Liên kết này có hiệu lực trong
        <strong>15 phút</strong>.
    </p>

    <p>
        Nếu bạn không yêu cầu thay đổi mật khẩu,
        bạn có thể bỏ qua email này.
    </p>

    <hr style="
        border:0;
        border-top:1px solid #eee;
        margin:30px 0;
    ">

    <small style="color:#999;">
        The Little Prince Pâtisserie
    </small>

</div>

HTML;


            $mail->AltBody =
                "Xin chào {$fullName}.\n\n" .
                "Đặt lại mật khẩu tại:\n" .
                $resetUrl .
                "\n\nLiên kết có hiệu lực trong 15 phút.";


            $mail->send();

        } catch (MailException $e) {

            throw new Exception(
                'Không thể gửi email. Vui lòng thử lại sau.'
            );
        }
    }
}