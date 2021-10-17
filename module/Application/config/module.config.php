<?php

declare(strict_types=1);

namespace Application;

use Application\Controller\Factory\ImageControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Controller\Factory\RegistrationControllerFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Regex;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'posts' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/posts[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\PostController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'about' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'about',
                    ],
                ],
            ],
            'contactus' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contact-us',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'contactUs',
                    ],
                ],
            ],
            'images' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/images[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\ImageController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'registration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/registration[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\RegistrationController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'my' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/my',
                    'defaults' => [
                        'controller' => Controller\MyController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'myGetJson' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/getJson',
                    'defaults' => [
                        'controller' => Controller\MyController::class,
                        'action'     => 'getJson',
                    ],
                ],
            ],
            'barcode' => [
                'type'    => Segment::class,
                'options' => [
                    'route'       => '/barcode[/:type/:label]',
                    'constraints' => [
                        'type'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'label' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults'    => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'barcode',
                    ],
                ],
            ],
            'doc' => [
                'type'    => Regex::class,
                'options' => [
                    'regex'    => '/doc(?<page>\/[a-zA-Z0-9_\-]+)\.html',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'doc',
                    ],
                    'spec'     => '/doc/%page%.html'
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\MyController::class           => InvokableFactory::class,
            Controller\IndexController::class        => IndexControllerFactory::class,
            Controller\ImageController::class        => ImageControllerFactory::class,
            Controller\RegistrationController::class => RegistrationControllerFactory::class,
            Controller\PostController::class         => Controller\Factory\PostControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\MailSender::class   => InvokableFactory::class,
            Service\ImageManager::class => InvokableFactory::class,
            Service\PostManager::class  => Service\Factory\PostManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
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
        'UserRegistrations'
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
