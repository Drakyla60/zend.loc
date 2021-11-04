<?php

declare(strict_types=1);

namespace Application;

use Application\Controller\Factory\ImageControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Controller\Factory\PostControllerFactory;
use Application\Controller\Factory\ProfileControllerFactory;
use Application\Controller\Factory\RegistrationControllerFactory;
use Application\Controller\ImageController;
use Application\Controller\IndexController;
use Application\Controller\ProfileController;
use Application\Controller\PostController;
use Application\Controller\RegistrationController;
use Application\Service\Factory\NavManagerFactory;
use Application\Service\Factory\PostManagerFactory;
use Application\Service\Factory\RbacAssertionManagerFactory;
use Application\Service\ImageManager;
use Application\Service\MailSender;
use Application\Service\NavManager;
use Application\Service\PostManager;
use Application\Service\RbacAssertionManager;
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
                        'controller' => IndexController::class,
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
                        'controller'    => PostController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'about' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/about',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'about',
                    ],
                ],
            ],
            'contactus' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contact-us',
                    'defaults' => [
                        'controller' => IndexController::class,
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
                        'controller'    => ImageController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'reg' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/reg[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller'    => RegistrationController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'profile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/profile[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => ProfileController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'myGetJson' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/getJson',
                    'defaults' => [
                        'controller' => ProfileController::class,
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
                        'controller' => IndexController::class,
                        'action'     => 'barcode',
                    ],
                ],
            ],
            'doc' => [
                'type'    => Regex::class,
                'options' => [
                    'regex'    => '/doc(?<page>\/[a-zA-Z0-9_\-]+)\.html',
                    'defaults' => [
                        'controller' => IndexController::class,
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
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            ProfileController::class      => ProfileControllerFactory::class,
            IndexController::class        => IndexControllerFactory::class,
            ImageController::class        => ImageControllerFactory::class,
            RegistrationController::class => RegistrationControllerFactory::class,
            PostController::class         => PostControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            MailSender::class   => InvokableFactory::class,
            ImageManager::class => InvokableFactory::class,
            PostManager::class  => PostManagerFactory::class,
            RbacAssertionManager::class => RbacAssertionManagerFactory::class,
            NavManager::class => NavManagerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/application_layout.phtml',
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
    ],
    'access_filter' => [
        'options' => [
            'mode' => 'restrictive' // restrictive  !!  permissive
        ],
        'controllers' => [
            IndexController::class => [
                ['actions' => ['index','contactUs', 'about', 'thankYou', 'sendError', 'barcode'], 'allow' => '*'],
            ],
            PostController::class => [
                ['actions' => ['add', 'view', 'edit', 'delete', 'admin'], 'allow' => '*'],
            ],
            ProfileController::class => [
                ['actions' => ['index', 'edit', 'settings'], 'allow' => '@'],
            ],
            ImageController::class => [
                ['actions' => ['index','upload', 'file'], 'allow' => '*'],
            ],
            RegistrationController::class => [
                ['actions' => ['index', 'review'], 'allow' => '*'],
            ],

        ]
    ],
];
