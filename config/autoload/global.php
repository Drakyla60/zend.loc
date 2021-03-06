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

use Application\Service\RbacAssertionManager;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PDOMySqlDriver;

return [

    'logger' => [
        'allLog' => './data/log/allLog.log'
    ],
    'images' => [
        'userImagesCatalog'        => './public/img/avatar/original/',
        'userImagesCatalog50x50'   => './public/img/avatar/50x50/',
        'userImagesCatalog150x150' => './public/img/avatar/150x150/',
        'postImagesCatalog'        => './public/img/posts/original/',
        'postImagesCatalog100x100' => './public/img/posts/100x100/',
        'postImagesCatalog300x300' => './public/img/posts/300x300/',
        'contactUsImagesCatalog'   => './public/img/contact-us/'
    ],
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
//@TODO треба переписати кеш, були проблеми перейшов на symfony/cache php8.1

//    'caches' => [
//        'FilesystemCache' => [
//            'adapter' => [
//                'name'    => Filesystem::class,
//                'options' => [
//                    // Store cached data in this directory.
//                    'cache_dir' => './data/cache',
//                    // Store cached data for 1 hour.
//                    'ttl' => 60*60*1
//                ],
//            ],
//            'plugins' => [
//                [
//                    'name'    => 'serializer',
//                    'options' => [],
//                ],
//            ],
//        ],
//    ],
    'rbac_manager' => [
        'assertions' => [RbacAssertionManager::class],
    ],

];
