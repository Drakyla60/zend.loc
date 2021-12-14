<?php

declare(strict_types=1);

namespace Admin;

use Admin\Controller\Factory\IndexControllerFactory;
use Admin\Controller\IndexController;
use Admin\Service\Factory\Parser\ParserFactory;
use Admin\Service\Factory\Parser\TrelloParserFactory;
use Admin\Service\Parser\Parser;
use Admin\Service\Parser\TrelloParser;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home_admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/parsing[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Parser::class   => ParserFactory::class,
            TrelloParser::class   => TrelloParserFactory::class,
//            Service\ImageManager::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/admin_layout.phtml',
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'session_containers' => [
        'ContainerNamespace',
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver::class,
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'odm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive' // restrictive  !!  permissive
        ],
        'controllers' => [
            IndexController::class => [
                ['actions' => '*', 'allow' => '+role.manage']
            ],
        ]
    ],
];
