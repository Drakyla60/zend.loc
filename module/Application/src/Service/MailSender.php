<?php

namespace Application\Service;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\InMemory;

class MailSender
{
    public function sendMail($sender, $recipient, $subject, $text): bool
    {
        $result = false;

        try {
            $mail = new Message();
            $mail->addFrom($sender);
            $mail->addTo($recipient);
            $mail->setSubject($subject);
            $mail->setBody($text);

            $transport = new InMemory();
            $transport->send($mail);

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}