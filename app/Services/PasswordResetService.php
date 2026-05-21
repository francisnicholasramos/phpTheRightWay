<?php

namespace App\Services;

use App\Models\PasswordReset;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class PasswordResetService {
    public static function sendResetLink(string $email): bool {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            return false;
        }

        $rawToken    = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $rawToken);
        $expiresAt   = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        /* $expiresAt   = date('Y-m-d H:i:s', time() + 10); */ // FOR TESTING 

        $resetModel = new PasswordReset();
        $resetModel->deleteByEmail($email);
        $resetModel->create($hashedToken, $email, $expiresAt);

        $resetUrl = rtrim($_ENV['APP_URL'], '/') . '/reset-password?token=' . $rawToken;

        return self::sendEmail($email, $user->first_name, $resetUrl);
    }

    public static function validateToken(string $rawToken): ?PasswordReset {
        $hashedToken = hash('sha256', $rawToken);

        $resetModel = new PasswordReset();
        $reset = $resetModel->findByToken($hashedToken);

        if (!$reset) {
            return null;
        }

        if (strtotime($reset->expires_at) < time()) {
            $resetModel->deleteByToken($hashedToken);
            return null;
        }

        return $reset;
    }

    public static function resetPassword(string $rawToken, string $newPassword): bool {
        $reset = self::validateToken($rawToken);

        if (!$reset) {
            return false;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($reset->email);

        if (!$user) {
            return false;
        }

        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $userModel->updatePassword($user->id, $hashed);

        $resetModel = new PasswordReset();
        $resetModel->deleteByToken(hash('sha256', $rawToken));

        return true;
    }

    private static function sendEmail(string $toEmail, string $firstName, string $resetUrl): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = (int) $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = 'Reset your password';
            $mail->Body    = "
                <p>Hi {$firstName},</p>
                <p>Click the link below to reset your password. This link expires in 1 hour.</p>
                <p><a href='{$resetUrl}'>{$resetUrl}</a></p>
                <p>If you did not request a password reset, ignore this email.</p>
            ";

            $mail->send();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
