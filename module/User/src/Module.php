<?php

declare(strict_types=1);

namespace User;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use User\Controller\AuthController;
use User\Service\AuthManager;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Цей метод викликається після завершення самозавантаження MVC і дозволяє
     * Реєструвати обробники подій.
     */
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }

    /**
     * Метод-обробник для події 'Dispatch'. Ми обробляємо подія Dispatch
     * Для виклику фільтру доступу. Фільтр доступу дозволяє визначити,
     * Чи може користувач переглядати сторінку. Якщо користувач не
     * Авторизований, і у нього немає прав для перегляду, ми відкривається його
     * На сторінку входу на сайт.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Отримуємо контролер і дію, якому був відправлений HTTP-запит.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Конвертуємо ім'я дії з пунктиром в ім'я в верблюжому регістрі.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Отримуємо екземпляр сервісу AuthManager.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Виконуємо фільтр доступу для кожного контролера крім AuthController
        // (щоб уникнути нескінченного перенаправлення).
        if ($controllerName != AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)) {

            // Запоминаем URL страницы, к которой пытался обратиться пользователь. Мы перенаправим пользователя
            // на этот URL после успешной авторизации.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Робимо URL відносним (прибираємо схему, інформацію про користувача, ім'я хоста і порт),
            // щоб уникнути перенаправлення на інший домен недоброзичливцем.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // перенаправляє користувача на сторінку "Login".
            return $controller->redirect()->toRoute('login', [],
                ['query' => ['redirectUrl' => $redirectUrl]]);
        }
    }
}
