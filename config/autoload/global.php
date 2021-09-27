<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
    // Налаштування сеcії.
    'session_config' => [
        // На який час записуєьбся кука в браузер.
        'cookie_lifetime' => 60*60*1,
        // Скільки зберігається сесія на сервері (30 днів)
        'gc_maxlifetime'     => 60*60*24*30,
    ],
    // Налаштування менеджера сесії.
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Налаштування де зберігається сесія.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];
