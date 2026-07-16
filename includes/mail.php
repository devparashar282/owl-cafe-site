<?php

function configureMailerFromEnv(\PHPMailer\PHPMailer\PHPMailer $mail): bool
{
    $host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
    $username = getenv('MAIL_USERNAME') ?: 'devparashar282@gmail.com';
    $password = getenv('MAIL_PASSWORD') ?: 'neoeantcliuyvcmm';

    if ($host === '' || $username === '' || $password === '') {
        return false;
    }

    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;

    $port = getenv('MAIL_PORT');
    $mail->Port = $port !== false && $port !== '' ? (int)$port : 587;

    $encryption = strtolower(getenv('MAIL_ENCRYPTION') ?: 'tls');
    if ($encryption === 'ssl' || $encryption === 'smtps') {
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($encryption === 'tls' || $encryption === 'starttls') {
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    }

    $fromEmail = getenv('MAIL_FROM_EMAIL') ?: $username;
    $fromName = getenv('MAIL_FROM_NAME') ?: 'OWL CAFE';
    $mail->setFrom($fromEmail, $fromName);

    $replyToEmail = getenv('MAIL_REPLY_TO');
    if ($replyToEmail) {
        $replyToName = getenv('MAIL_REPLY_TO_NAME') ?: $fromName;
        $mail->addReplyTo($replyToEmail, $replyToName);
    }

    return true;
}
