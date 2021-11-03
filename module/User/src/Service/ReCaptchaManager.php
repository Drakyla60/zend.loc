<?php

namespace User\Service;

use Laminas\ReCaptcha\ReCaptcha;

class ReCaptchaManager
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function init()
    {
        $config = $this->config['captcha']['reCaptcha'];

        return new ReCaptcha($config['publicKey'], $config['privateKey']);
    }


    public function checkReCaptcha($captcha): bool
    {
        $recaptcha = $this->init();

        $verify = $recaptcha->verify($captcha);

        if ($verify->isValid()) {
            return true;
        }

        return false;
    }

}