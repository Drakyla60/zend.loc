<?php

declare(strict_types=1);

namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use User\Controller\AuthController;
use User\Controller\Factory\AuthControllerFactory;
use User\Controller\Factory\PermissionControllerFactory;
use User\Controller\Factory\RoleControllerFactory;
use User\Controller\Factory\UserControllerFactory;
use User\Controller\PermissionController;
use User\Controller\Plugin\AccessPlugin;
use User\Controller\Plugin\Factory\AccessPluginFactory;
use User\Controller\RoleController;
use User\Controller\UserController;
use User\Service\Factory\AuthAdapterFactory;
use User\Service\Factory\AuthManagerFactory;
use User\Service\Factory\PermissionManagerFactory;
use User\Service\Factory\RbacManagerFactory;
use User\Service\Factory\RoleManagerFactory;
use User\Service\Factory\UserManagerFactory;
use User\Service\Factory\AuthenticationServiceFactory;
use User\Service\AuthAdapter;
use User\Service\AuthManager;
use User\Service\PermissionManager;
use User\Service\RbacManager;
use User\Service\RoleManager;
use User\Service\UserManager;
use User\View\Helper\Access;
use User\View\Helper\Factory\AccessFactory;

return [
    'router'             => [
        'routes' => [
            'home_user' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/users/',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'not-authorized' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/not-authorized',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'notAuthorized',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/reset-password',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'resetPassword',
                    ],
                ],
            ],
            'set-password' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/set-password',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'setPassword',
                    ],
                ],
            ],
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => UserController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'roles' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/roles[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => RoleController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'permissions' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/permissions[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => PermissionController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'session_containers' => [
        'UserSessionContainer',
    ],
    'controllers'        => [
        'factories' => [
            UserController::class       => UserControllerFactory::class,
            AuthController::class       => AuthControllerFactory::class,
            RoleController::class       => RoleControllerFactory::class,
            PermissionController::class => PermissionControllerFactory::class,
        ],
    ],
    'service_manager'    => [
        'factories' => [
            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthAdapter::class           => AuthAdapterFactory::class,
            UserManager::class           => UserManagerFactory::class,
            AuthManager::class           => AuthManagerFactory::class,
            RoleManager::class           => RoleManagerFactory::class,
            RbacManager::class           => RbacManagerFactory::class,
            PermissionManager::class     => PermissionManagerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            AccessPlugin::class => AccessPluginFactory::class,
        ],
        'aliases' => [
            'access' => AccessPlugin::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            Access::class => AccessFactory::class,
        ],
        'aliases' => [
            'access' => Access::class,
        ],
    ],
    'view_manager'       => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'        => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'strategies'          => [
            'ViewJsonStrategy',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine'           => [
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
            Controller\UserController::class => [
                // Дать доступ к действиям "resetPassword", "message" и "setPassword" всем.
                ['actions' => ['resetPassword', 'message', 'setPassword'], 'allow' => '*'],
                // Дать доступ к действиям "index", "add", "edit", "view", "changePassword"
                // пользователям с привилегией "user.manage".
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'],
                    'allow' => '+user.manage']
            ],
            Controller\RoleController::class => [
                // Разрешить доступ аутентифицированным пользователям с привилегией "role.manage".
                ['actions' => '*', 'allow' => '+role.manage']
            ],
            Controller\PermissionController::class => [
                // Разрешить доступ аутентифицированным пользователям с привилегией "permission.manage".
                ['actions' => '*', 'allow' => '+permission.manage']
            ],
        ]
    ],


//    'access_filter'      => [
//        'options' => [
//            'mode' => 'restrictive' // restrictive  !!  permissive
//        ],
//        'controllers' => [
//            UserController::class => [
//                ['actions' => ['index'], 'allow' => '*'],
//                ['actions' => ['add'], 'allow' => '@']
//            ],
//            IndexController::class => [
//                ['actions' => ['index'], 'allow' => '*'],
//                ['actions' => ['about'], 'allow' => '@']
//            ],
//        ]
//    ],
];
