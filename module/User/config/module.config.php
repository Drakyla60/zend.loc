<?php

declare(strict_types=1);

namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Router\Http\Segment;
use User\Controller\Factory\IndexControllerFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use User\Service\AuthAdapter;
use User\Service\AuthManager;
use User\Service\Factory\AuthServiceFactory;
use User\Service\UserManager;
use User\Service\Factory\AuthManagerFactory;
use User\Service\Factory\UserManagerFactory;

return [
    'router' => [
        'routes' => [
            'home_user' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/user',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:action]',
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
            Controller\IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthenticationService::class  => Service\Factory\AuthServiceFactory::class,
            UserManager::class => UserManagerFactory::class,
            AuthManager::class => AuthManagerFactory::class,
            AuthAdapter::class => AuthServiceFactory::class
//            Service\MailSender::class   => InvokableFactory::class,
//            Service\PostManager::class  => Service\Factory\PostManagerFactory::class,
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
            'application/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
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
        'UserRegistration',
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
    // Ключ 'access_filter' используется модулем User, чтобы разрешить или запретить доступ к
// определенным действиям контроллера для не вошедших на сайт пользователей.
    'access_filter' => [
        'options' => [
            // Фильтр доступа может работать в 'ограничительном' (рекомендуется) или 'разрешающем'
            // режиме. В ограничительном режиме все действия контроллера должны быть явно перечислены
            // под ключом конфигурации 'access_filter', а доступ к любому не перечисленному действию
            // для неавторизованных пользователей запрещен. В разрешающем режиме, даже если действие не
            // указано под ключом 'access_filter', доступ к нему разрешен для всех (даже для
            // неавторизованных пользователей. Рекомендуется использовать более безопасный ограничительный режим.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Позволяем всем обращаться к действиям "index" и "about".
                ['actions' => ['index', 'about'], 'allow' => '*'],
                // Позволяем вошедшим на сайт пользователям обращаться к действию "settings".
                ['actions' => ['settings'], 'allow' => '@']
            ],
        ]
    ],
];
