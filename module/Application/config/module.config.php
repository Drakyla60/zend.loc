<?php

declare(strict_types=1);

namespace Application;

use Application\View\Admin\Helper\Access;
use Application\View\Admin\Helper\Factory\AccessFactory;
use Application\Controller\Admin\Controller\AuthController;
use Application\Controller\Admin\Controller\Factory\AuthControllerFactory;
use Application\Controller\Admin\Controller\Factory\PermissionControllerFactory;
use Application\Controller\Admin\Controller\Factory\PostCategoryControllerFactory;
use Application\Controller\Admin\Controller\Factory\PostControllerFactory;
use Application\Controller\Admin\Controller\Factory\PostTagControllerFactory;
use Application\Controller\Admin\Controller\Factory\RoleControllerFactory;
use Application\Controller\Admin\Controller\Plugin\AccessPlugin;
use Application\Controller\Admin\Controller\Plugin\Factory\AccessPluginFactory;
use Application\Controller\Admin\Controller\Plugin\Factory\LoggerPluginFactory;
use Application\Controller\Admin\Controller\Plugin\LoggerPlugin;
use Application\Service\Admin\AuthAdapter;
use Application\Service\Admin\AuthManager;
use Application\Service\Admin\Factory\AuthAdapterFactory;
use Application\Service\Admin\Factory\AuthenticationServiceFactory;
use Application\Service\Admin\Factory\AuthManagerFactory;
use Application\Service\Admin\Factory\ImageManagerFactory;
use Application\Service\Admin\Factory\LoggerManagerFactory;
use Application\Service\Admin\Factory\MailManagerFactory;
use Application\Service\Admin\Factory\PermissionManagerFactory;
use Application\Service\Admin\Factory\PostCategoryManagerFactory;
use Application\Service\Admin\Factory\PostManagerFactory;
use Application\Service\Admin\Factory\PostTagManagerFactory;
use Application\Service\Admin\Factory\RbacManagerFactory;
use Application\Service\Admin\Factory\ReCaptchaManagerFactory;
use Application\Service\Admin\Factory\RoleManagerFactory;
use Application\Service\Admin\Factory\UserManagerFactory;
use Application\Service\Admin\LoggerManager;
use Application\Service\Admin\MailManager;
use Application\Service\Admin\PermissionManager;
use Application\Service\Admin\PostCategoryManager;
use Application\Service\Admin\PostManager;
use Application\Service\Admin\PostTagManager;
use Application\Service\Admin\RbacManager;
use Application\Service\Admin\ReCaptchaManager;
use Application\Service\Admin\RoleManager;
use Application\Service\Admin\UserManager;
use Application\Controller\Admin\Controller\Factory\UserControllerFactory;
use Application\Controller\Admin\Controller\PermissionController;
use Application\Controller\Admin\Controller\PostCategoryController;
use Application\Controller\Admin\Controller\PostController;
use Application\Controller\Admin\Controller\PostTagController;
use Application\Controller\Admin\Controller\RoleController;
use Application\Controller\Admin\Controller\UserController;
use Application\Controller\Factory\ImageControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
//use Application\Controller\Factory\PostControllerFactory;
use Application\Controller\Factory\ProfileControllerFactory;
use Application\Controller\Factory\RegistrationControllerFactory;
use Application\Controller\ImageController;
use Application\Controller\IndexController;
use Application\Controller\ProfileController;
//use Application\Controller\PostController;
use Application\Controller\RegistrationController;
use Application\Service\Factory\NavManagerFactory;
//use Application\Service\Factory\PostManagerFactory;
use Application\Service\Factory\RbacAssertionManagerFactory;
use Application\Service\ImageManager;
use Application\Service\MailSender;
use Application\Service\NavManager;
//use Application\Service\PostManager;
use Application\Service\RbacAssertionManager;
use Application\View\Helper\Breadcrumbs;
use Application\View\Helper\Factory\MenuFactory;
use Application\View\Helper\Menu;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Authentication\AuthenticationService;
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
            'profile_settings' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/profile/settings',
                    'defaults' => [
                        'controller' => ProfileController::class,
                        'action'     => 'settings',
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
            'home_s' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => \Application\Controller\Admin\Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'home_admin' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admin/',
                    'defaults' => [
                        'controller' => \Application\Controller\Admin\Controller\IndexController::class,
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
    'controllers' => [
        'factories' => [
            ProfileController::class      => ProfileControllerFactory::class,
            IndexController::class        => IndexControllerFactory::class,
            ImageController::class        => ImageControllerFactory::class,
//            PostController::class         => PostControllerFactory::class,

            UserController::class         => UserControllerFactory::class,
            AuthController::class         => AuthControllerFactory::class,
            RoleController::class         => RoleControllerFactory::class,
            Controller\Admin\Controller\IndexController::class        => \Application\Controller\Admin\Controller\Factory\IndexControllerFactory::class,
            PermissionController::class   => PermissionControllerFactory::class,
            PostController::class         => PostControllerFactory::class,
            PostTagController::class      => PostTagControllerFactory::class,
            PostCategoryController::class => PostCategoryControllerFactory::class,

        ],
    ],
    'service_manager' => [
        'factories' => [
            MailSender::class   => InvokableFactory::class,
            ImageManager::class => InvokableFactory::class,
//            PostManager::class  => PostManagerFactory::class,
            RbacAssertionManager::class => RbacAssertionManagerFactory::class,
            NavManager::class => NavManagerFactory::class,

            AuthenticationService::class => AuthenticationServiceFactory::class,
            AuthAdapter::class           => AuthAdapterFactory::class,
            UserManager::class           => UserManagerFactory::class,
            AuthManager::class           => AuthManagerFactory::class,
            RoleManager::class           => RoleManagerFactory::class,
            RbacManager::class           => RbacManagerFactory::class,
            MailManager::class           => MailManagerFactory::class,
            \Application\Service\Admin\ImageManager::class          => ImageManagerFactory::class,
            LoggerManager::class         => LoggerManagerFactory::class,
            ReCaptchaManager::class      => ReCaptchaManagerFactory::class,
            PermissionManager::class     => PermissionManagerFactory::class,
            PostManager::class           => PostManagerFactory::class,
            PostCategoryManager::class   => PostCategoryManagerFactory::class,
            PostTagManager::class        => PostTagManagerFactory::class,
        ],
    ],
//    'rbac_manager' => [ 'assertions' => [Service\RbacAssertionManager::class], ],
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
            Menu::class => MenuFactory::class,
            Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => Menu::class,
            'pageBreadcrumbs' => Breadcrumbs::class,
            'access' => Access::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
//        'not_found_template'       => 'application/error/404',
//        'exception_template'       => 'application/error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/application_layout.phtml',
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
        'UserRegistrations',
        'UserSessionContainer',
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
        'controllers' => array(
            IndexController::class => array(
                array('actions' => array('index','contactUs', 'about', 'thankYou', 'sendError', 'barcode'), 'allow' => '*'),
            ),
//            PostController::class => [
//                ['actions' => ['index', 'add', 'view', 'edit', 'delete', 'admin'], 'allow' => '*'],
//            ],
            ProfileController::class => array(
                array('actions' => array('index', 'edit', 'settings','profile', 'security', 'notifications', 'getJson'), 'allow' => '@'),
            ),
            ImageController::class => array(
                array('actions' => array('index','upload', 'file'), 'allow' => '*'),
            ),

            UserController::class => array(
                // Дать доступ к действиям "resetPassword", "message" и "setPassword" всем.
                array('actions' => array('resetPassword', 'message', 'setPassword', 'emailConfirmation'), 'allow' => '*'),
                // Дать доступ к действиям "index", "add", "edit", "view", "changePassword"
                // пользователям с привилегией "user.manage".
                array('actions' => array('index', 'add', 'edit', 'view', 'changePassword'),
                    'allow' => '+user.manage')
            ),
            PostController::class => array(
                array('actions' => array('index', 'add', 'view', 'edit', 'delete', 'restore'), 'allow' => '+role.manage'),
            ),
            Controller\Admin\Controller\IndexController::class => array(
                array('actions' => '*', 'allow' => '+role.manage')
            ),
            RoleController::class => array(
                array('actions' => '*', 'allow' => '+role.manage')
            ),
            PermissionController::class => array(
                array('actions' => '*', 'allow' => '+permission.manage')
            ),
            PostCategoryController::class => array(
                array('actions' => '*', 'allow' => '+permission.manage')
            ),
            PostTagController::class => array(
                array('actions' => '*', 'allow' => '+permission.manage')
            ),
        )
    ],
];
