<?php

namespace Application\Service;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\InMemory;
use Laminas\Mail\Transport\Sendmail;

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

//            $transport = new Sendmail();
//            $transport->send($mail);

            $transport = new InMemory();
            $transport->send($mail);

            $received = $transport->getLastMessage();

            $result = true;
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
}