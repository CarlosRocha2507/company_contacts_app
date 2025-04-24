<?php
# PHP mailer plugins
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
# PHPMailer Autoload
require __DIR__ . '/../plugins/vendor/autoload.php';
class ReportService
{
    /**
     * Sends a report with the provided details.
     *
     * @param string $name The name of the person sending the report.
     * @param string $email The email address of the sender.
     * @param string $message The message content of the report.
     * @return bool Returns true if the report was sent successfully, otherwise throws an exception.
     * @throws Exception If there is an error while sending the email.
     */
    public static function sendReport($name, $email, $message)
    {
        try {
            require __DIR__ . '/../config/config.php';
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $config['Email']['CompanyEmail']['HOST'];
            $mail->SMTPAuth = $config['Email']['CompanyEmail']['SMTAuth'];
            $mail->Username = $config['Email']['CompanyEmail']['USERNAME'];
            $mail->Password = $config['Email']['CompanyEmail']['PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $config['Email']['CompanyEmail']['PORT'];
            $mail->setFrom($config['Email']['CompanyEmail']['USERNAME'], 'Company Email');
            $mail->addAddress($config['Email']['CompanyEmail']['TOMAIL'], $name);
            $mail->addReplyTo($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Issue Reported';
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Body = self::workedMessage($message, $name, $email);
            $mail->AltBody = self::workedMessage($message, $name, $email);
            $mail->send();
            return true;
        } catch (Exception $ex) {
            die("ERROR: " . $ex->getMessage() . "<br>FILE: " . $ex->getFile() . "<br>LINE: " . $ex->getLine());
        }
    }
    /**
     * Generates a formatted message indicating work-related information.
     *
     * @param string $message The main message content.
     * @param string $name The name of the individual associated with the message.
     * @param string $email The email address of the individual associated with the message.
     * @return string The formatted work-related message.
     */
    private static function workedMessage($message, $name, $email)
    {
        $message = "Isssue reported by: $name,<br><br>Contact Email$email:<br><br>message:$message";
        return $message;
    }
}