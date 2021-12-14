<?php

declare(strict_types=1);

namespace Admin;

use Admin\Controller\Factory\IndexControllerFactory;
use Laminas\Authentication\AuthenticationService;
use Laminas\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Admin\Controller\AuthController;
use Admin\Controller\Factory\AuthControllerFactory;
use Admin\Controller\Factory\PermissionControllerFactory;
use Admin\Controller\Factory\PostCategoryControllerFactory;
use Admin\Controller\Factory\PostControllerFactory;
use Admin\Controller\Factory\PostTagControllerFactory;
use Admin\Controller\Factory\RoleControllerFactory;
use Admin\Controller\Factory\UserControllerFactory;
use Admin\Controller\IndexController;
use Admin\Controller\PermissionController;
use Admin\Controller\Plugin\AccessPlugin;
use Admin\Controller\Plugin\Factory\AccessPluginFactory;
use Admin\Controller\Plugin\Factory\LoggerPluginFactory;
use Admin\Controller\Plugin\LoggerPlugin;
use Admin\Controller\PostCategoryController;
use Admin\Controller\PostController;
use Admin\Controller\PostTagController;
use Admin\Controller\RoleController;
use Admin\Controller\UserController;
use Admin\Service\Factory\AuthAdapterFactory;
use Admin\Service\Factory\AuthManagerFactory;
use Admin\Service\Factory\ImageManagerFactory;
use Admin\Service\Factory\LoggerManagerFactory;
use Admin\Service\Factory\MailManagerFactory;
use Admin\Service\Factory\PermissionManagerFactory;
use Admin\Service\Factory\PostCategoryManagerFactory;
use Admin\Service\Factory\PostManagerFactory;
use Admin\Service\Factory\PostTagManagerFactory;
use Admin\Service\Factory\RbacManagerFactory;
use Admin\Service\Factory\ReCaptchaManagerFactory;
use Admin\Service\Factory\RoleManagerFactory;
use Admin\Service\Factory\UserManagerFactory;
use Admin\Service\Factory\AuthenticationServiceFactory;
use Admin\Service\AuthAdapter;
use Admin\Service\AuthManager;
use Admin\Service\ImageManager;
use Admin\Service\LoggerManager;
use Admin\Service\MailManager;
use Admin\Service\PermissionManager;
use Admin\Service\PostCategoryManager;
use Admin\Service\PostManager;
use Admin\Service\PostTagManager;
use Admin\Service\RbacManager;
use Admin\Service\ReCaptchaManager;
use Admin\Service\RoleManager;
use Admin\Service\UserManager;
use Admin\View\Helper\Access;
use Admin\View\Helper\Factory\AccessFactory;

return [
    'router'             => [
        'routes' => [
            'home_s' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'home_admin' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin/',
                    'defaults' => [
                        'controller' => IndexController::class,
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
            'registration' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/registration',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'registration',
                    ],
                ],
            ],
            'email-confirmation' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/email-confirmation',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'emailConfirmation',
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

            'posts' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/posts[/:action[/:id]]',
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
            'posts-tags' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/posts-tags[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => PostTagController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'posts-category' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/posts-category[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => PostCategoryController::class,
                        'action'        => 'index',
                    ],
                ],
            ],

            'user' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin/users/',
                    'defaults' => [
                        'controller' => UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/users[/:action[/:id]]',
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
                    'route'    => '/admin/roles[/:action[/:id]]',
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
                    'route'    => '/admin/permissions[/:action[/:id]]',
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
            UserController::class         => UserControllerFactory::class,
            AuthController::class         => AuthControllerFactory::class,
            RoleController::class         => RoleControllerFactory::class,
            IndexController::class        => IndexControllerFactory::class,
            PermissionController::class   => PermissionControllerFactory::class,
            PostController::class         => PostControllerFactory::class,
            PostTagController::class      => PostTagControllerFactory::class,
            PostCategoryController::class => PostCategoryControllerFactory::class,
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
            MailManager::class           => MailManagerFactory::class,
            ImageManager::class          => ImageManagerFactory::class,
            LoggerManager::class         => LoggerManagerFactory::class,
            ReCaptchaManager::class      => ReCaptchaManagerFactory::class,
            PermissionManager::class     => PermissionManagerFactory::class,
            PostManager::class           => PostManagerFactory::class,
            PostCategoryManager::class   => PostCategoryManagerFactory::class,
            PostTagManager::class        => PostTagManagerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            AccessPlugin::class => AccessPluginFactory::class,
            LoggerPlugin::class => LoggerPluginFactory::class,
        ],
        'aliases' => [
            'access' => AccessPlugin::class,
            'logger' => LoggerPlugin::class,
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
            'layout/layout'           => __DIR__ . '/../view/layout/users_layout.phtml',
            'user/index/index'        => __DIR__ . '/../view/admin/index/index.phtml',
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
            UserController::class => [
                // Дать доступ к действиям "resetPassword", "message" и "setPassword" всем.
                ['actions' => ['resetPassword', 'message', 'setPassword', 'emailConfirmation'], 'allow' => '*'],
                // Дать доступ к действиям "index", "add", "edit", "view", "changePassword"
                // пользователям с привилегией "user.manage".
                ['actions' => ['index', 'add', 'edit', 'view', 'changePassword'],
                    'allow' => '+user.manage']
            ],
            PostController::class => [
                ['actions' => ['index', 'add', 'view', 'edit', 'delete', 'restore'], 'allow' => '+role.manage'],
            ],
            IndexController::class => [
                ['actions' => '*', 'allow' => '+role.manage']
            ],
            RoleController::class => [
                ['actions' => '*', 'allow' => '+role.manage']
            ],
            PermissionController::class => [
                ['actions' => '*', 'allow' => '+permission.manage']
            ],
            PostCategoryController::class => [
                ['actions' => '*', 'allow' => '+permission.manage']
            ],
            PostTagController::class => [
                ['actions' => '*', 'allow' => '+permission.manage']
            ],
        ]
    ],
];
