<?php

namespace Application\Controller\Admin\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class LoggerPlugin extends AbstractPlugin
{
    private $loggerManager;

    public function __construct($loggerManager)
    {
        $this->loggerManager = $loggerManager;
    }

    public function __invoke($type, $text)
    {
        return $this->loggerManager->logger($type, $text);
//        $logger->$type($text);
    }
}