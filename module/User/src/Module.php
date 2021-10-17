<?php

declare(strict_types=1);

namespace User;

use Laminas\ModuleManager\ModuleManager;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use User\Controller\AuthController;
use User\Service\AuthManager;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
    // Метод "init" викликається при запуску програми і
    // дозволяє зареєструвати обробник подій.

    public function init(ModuleManager $manager)
    {
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(__NAMESPACE__, 'dispatch',
            [$this, 'onDispatch'], 100);

//        $sharedEventManager->attach(AbstractActionController::class,
//            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

    }

    /**
     * Метод-обработчик для события 'Dispatch'. Мы обрабатываем событие Dispatch
     * для вызова фильтра доступа. Фильтр доступа позволяет определить,
     * может ли пользователь просматривать страницу. Если пользователь не
     * авторизован, и у него нет прав для просмотра, мы перенаправляем его
     * на страницу входа на сайт.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Получаем контроллер и действие, которому был отправлен HTTP-запрос.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Конвертируем имя действия с пунктирами в имя в верблюжьем регистре.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Получаем экземпляр сервиса AuthManager.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Выполняем фильтр доступа для каждого контроллера кроме AuthController
        // (чтобы избежать бесконечного перенаправления).
        if ($controllerName!=AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Запоминаем URL страницы, к которой пытался обратиться пользователь. Мы перенаправим пользователя
            // на этот URL после успешной авторизации.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Делаем URL относительным (убираем схему, информацию о пользователе, имя хоста и порт),
            // чтобы избежать перенаправления на другой домен недоброжелателем.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Перенаправляем пользователя на страницу "Login".
            return $controller->redirect()->toRoute('login', [],
                ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        $sessionManager = $serviceManager->get(SessionManager::class);
    }

}
