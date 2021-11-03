<?php

namespace User\Service;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class MailManager
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $user
     * @param array $option = ['subjectEmail', 'bodyHtml']
     * @return bool
     * @throws Exception
     */
    public function sendMail($user, array $option = []): bool
    {
        $config = $this->config['mailer']['phpMailer'];
        //@TODO Винести то колись звідси
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->Encoding = 'base64';
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $config['host'];                        //Set the SMTP server to send through
        $mail->SMTPAuth   = $config['smtpAuth'];                    //Enable SMTP authentication
        $mail->Username   = $config['username'];                    //SMTP username
        $mail->Password   = $config['password'];                    //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $config['port'];                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($config['username'], $config['adminName']);
        $mail->addAddress($user->getEmail(), $user->getFullName());  //Add a recipient

        //Content
        $mail->isHTML($config['isHTML']);                            //Set email format to HTML
        $mail->Subject = $option['subjectEmail'];
        $mail->Body    = $option['bodyHtml'];

        if(!$mail->send()) {
            throw new Exception('Сталася помилка. Лист не надіслано.');
        }
        return true;
    }

    public function sendMailWithContactUs($user, array $option = []): bool
    {
        $config = $this->config['mailer']['phpMailer'];
        //@TODO Винести то колись звідси
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->Encoding = 'base64';
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $config['host'];                        //Set the SMTP server to send through
        $mail->SMTPAuth   = $config['smtpAuth'];                    //Enable SMTP authentication
        $mail->Username   = $config['username'];                    //SMTP username
        $mail->Password   = $config['password'];                    //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $config['port'];                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($user['email']);
        $mail->addAddress($config['adminEmail']);  //Add a recipient

        //Content
        $mail->isHTML($config['isHTML']);                            //Set email format to HTML
        $mail->Subject = $option['subjectEmail'];
        $mail->Body    = $option['bodyHtml'];
        if ($option['file']){
            $mail->addAttachment($option['file']);
        }

        if(!$mail->send()) {
            throw new Exception('Сталася помилка. Лист не надіслано.');
        }
        return true;
    }
}