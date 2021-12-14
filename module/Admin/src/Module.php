<?php

declare(strict_types=1);

namespace Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Admin\Controller\AuthController;
use Admin\Service\AuthManager;

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

//        $viewModel = $event->getViewModel();
//        $viewModel->setTemplate('layout/users_layout');


        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();

        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $sessionManager = $event->getApplication()->getServiceManager()->get(SessionManager::class);

        $this->forgetInvalidSession($sessionManager);
    }

    /**
     * Метод оброблювача подій для події Dispatch. Ми обробляємо подію Dispatch
     * І викликаємо фільтр доступу. Фільтр доступу дозволяє визначити, чи дозволено поточному
     * відвідувачу переглядати сторінку чи ні. Якщо він не авторизований і доступ до сторінки
     * для нього заборонено, ми перенаправляємо такого користувача на сторінку входу на сайт.
     */
    public function onDispatch(MvcEvent $event)
    {

        // Отримуємо контролер і дію, якого було відправлено HTTP-запит.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Конвертуємо написане через дефіс ім'я дії верблюжий регістр.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Отримуємо екземпляр сервісу AuthManager.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Застосовуємо фільтр доступу до кожного контролера крім AuthController
        // (щоб уникнути нескінченного перенаправления).
        if ($controllerName!=AuthController::class)
        {
            $result = $authManager->filterAccess($controllerName, $actionName);

            if ($result==AuthManager::AUTH_REQUIRED) {

                // Запам'ятовуємо URL сторінки, яку намагався перейти користувач. Ми
                // Перенаправимо користувача на цю URL після його успішного входу на сайт.
                $uri = $event->getApplication()->getRequest()->getUri();
                // Робимо URL-адресу відносним (прибираємо схему, відомості про користувача, ім'я хоста та порт),
                // щоб уникнути перенаправлення в інший домен зловмисниками.
                $uri->setScheme(null)
                    ->setHost(null)
                    ->setPort(null)
                    ->setUserInfo(null);
                $redirectUrl = $uri->toString();

                // Перенаправляємо користувача на сторінку "Login".
                return $controller->redirect()->toRoute('login', [],
                    ['query'=>['redirectUrl'=>$redirectUrl]]);
            }
            else if ($result==AuthManager::ACCESS_DENIED) {
                // Перенаправляємо користувача на сторінку "Not Authorized".
                return $controller->redirect()->toRoute('not-authorized');
            }
        }
    }

    private function forgetInvalidSession($sessionManager)
    {
        try {
            $sessionManager->start();
            return;
        } catch (\Exception $e) {
            echo 'Пздц)';
        }
        /**
         * Session validation failed: toast it and carry on.
         */
        $sessionManager->destroy();
    }
}
