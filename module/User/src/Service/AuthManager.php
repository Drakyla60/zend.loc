<?php

namespace User\Service;

use Laminas\Authentication\Result;

class AuthManager
{
    private $authService;
    private $sessionManager;

    public function __construct($authService, $sessionManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }

    public function login($email, $password, $rememberMe)
    {
        // Перевіряємо, увійшов користувач в систему. Якщо так, не дозволяємо
        // йому увійти двічі.
        if ($this->authService->getIdentity() != null) {
            throw new \Exception('Already logged in');
        }

        // Аутентіфіціруем користувача.
        $authAdapter = $this->authService->getAdapter();
        $authAdapter->setEmail($email);
        $authAdapter->setPassword($password);
        $result = $this->authService->authenticate();


        // Якщо користувач хоче, щоб його запамятали
        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
            $this->sessionManager->rememberMe();
        }

        return $result;
    }

    public function logout()
    {
        if ($this->authService->getIdentity() == null) {
            throw new \Exception('The user is not logged in');
        }

        $this->authService->clearIdentity();
    }

//    public function filterAccess($controllerName, $actionName)
//    {
//        // Определяем режим - 'ограничительный' (по умолчанию) или 'разрешающий'. В ограничительном
//        // режиме все действия контроллеров должны быть явно перечислены под ключом конфигурации 'access_filter',
//        // и для неавторизованных пользователей доступ будет запрещен к любому не указанному в этом списке действию.
//        // В разрешающем режиме, если действие не указано под ключом 'access_filter' доступ к нему все равно
//        // разрешен для всех (даже для неавторизованных пользователей). Рекомендуется использовать более безопасный
//        // ограничительный режим.
//        $mode = isset($this->config['options']['mode']) ? $this->config['options']['mode'] : 'restrictive';
//        if ($mode != 'restrictive' && $mode != 'permissive')
//            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');
//
//        if (isset($this->config['controllers'][$controllerName])) {
//            $items = $this->config['controllers'][$controllerName];
//            foreach ($items as $item) {
//                $actionList = $item['actions'];
//                $allow = $item['allow'];
//                if (is_array($actionList) && in_array($actionName, $actionList) ||
//                    $actionList == '*') {
//                    if ($allow == '*')
//                        return true; // Все могут просматривать страницу.
//                    else if ($allow == '@' && $this->authService->hasIdentity()) {
//                        return true; // Только аутентифицированный пользователь может просматривать страницу.
//                    } else {
//                        return false; // В доступе отказано.
//                    }
//                }
//            }
//        }
//
//        // В ограничительном режиме мы запрещаем неавторизованным пользователям доступ к любому действию,
//        // не перечисленному под ключом 'access_filter' (из соображений безопасности).
//        if ($mode == 'restrictive' && !$this->authService->hasIdentity())
//            return false;
//
//        // Разрешаем доступ к этой странице.
//        return true;
//    }
}