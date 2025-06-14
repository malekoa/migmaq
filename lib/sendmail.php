<?php
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Sends an email using PHPMailer.
 *
 * @param string $toEmail  Recipient's email address
 * @param string $toName   Recipient's name
 * @param string $subject  Email subject
 * @param string $body     Email body (HTML or plain text)
 * @param bool   $isHtml   Whether to send as HTML (default: false)
 * @param string|null $altBody Optional plain-text version of email
 * @return bool True if sent, false otherwise
 */
function sendMail(string $toEmail, string $toName, string $subject, string $body, bool $isHtml = false, ?string $altBody = null): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USERNAME');
        $mail->Password   = getenv('SMTP_PASSWORD'); // no spaces
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(getenv('SMTP_USERNAME'), 'Learn Mi\'gmaq');
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML($isHtml);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if ($isHtml && $altBody !== null) {
            $mail->AltBody = $altBody;
        }

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}