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
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PDOMySqlDriver;

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

//    'doctrine' => [
//        'connection' => [
//            'orm_default' => [
//                'params' => [
//                    'driver'   => 'mysql',
//                    'host'     => 'db',
//                    'port'     => '3306',
//                    'user'     => 'root',
//                    'password' => 'root',
//                    'dbname'   => 'laminas_blog',
//                    'driverOptions' => [
//                        1002   => 'SET NAMES utf8',
//                    ],
//                ],
//            ],
//        ],
//        'migrations_configuration' => [
//            'orm_default' => [
//                'table_storage' => [
//                    'table_name' => 'doctrine_migration_versions',
//                    'version_column_name' => 'version',
//                    'version_column_length' => 1024,
//                    'executed_at_column_name' => 'executed_at',
//                    'execution_time_column_name' => 'execution_time',
//                ],
//                'migrations_paths' => [
//                    'Migrations' => './data/Migrations'
//                ],
//
//                'organize_migrations' => 'year', // year or year_and_month
//                'custom_template' => null,
//                'all_or_nothing' => true,
//                'transactional' => true,
//                'check_database_platform' => true,
//                'connection' => null,
//                'em' => null,
//            ],
//        ],
//    ],
];
