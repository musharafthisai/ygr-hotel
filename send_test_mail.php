<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    // SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // YOUR GMAIL
    $mail->Username   = 'rahulrahuli1999@gmail.com';

    // APP PASSWORD (NO SPACES)
    $mail->Password   = 'ttwbbujfbvinpuhm';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // SENDER
    $mail->setFrom('rahulrahuli1999@gmail.com', 'YGR signature');

    // RECEIVER
    $mail->addAddress('infoccubetech@gmail.com');

    // EMAIL CONTENT
    $mail->isHTML(true);

    $mail->Subject = 'PHPMailer Test Mail';

    $mail->Body = '
        <h2>Email Working Successfully</h2>
        <p>This email was sent using PHPMailer with Gmail SMTP.</p>
    ';

    // SEND MAIL
    $mail->send();

    echo "Message has been sent successfully!";

} catch (Exception $e) {

    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>