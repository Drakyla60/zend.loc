<?php

namespace User\Service;

use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;

class LoggerManager
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }


    /**
     *      Type EMERG ALERT CRIT ERR WARN NOTICE INFO DEBUG
     * @param $type
     * @param $text
     */
    public function logger($type, $text)
    {
        $config = $this->config['logger'];
        $writer = new Stream($config['allLog'],'a');
        $logger = new Logger();
        $logger->addWriter($writer);

        $name = $logger->$type($text);
    }
}