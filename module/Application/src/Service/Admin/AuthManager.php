<?php

namespace Application\Service\Admin;

use Exception;
use Laminas\Authentication\Result;

class AuthManager
{

    // Constants returned by the access filter.
    const ACCESS_GRANTED = 1; // Access to the page is granted.
    const AUTH_REQUIRED  = 2; // Authentication is required to see the page.
    const ACCESS_DENIED  = 3; // Access to the page is denied.

    private $authService;
    private $sessionManager;
    private $config;
    private $rbacManager;

    public function __construct($authService, $sessionManager, $config, $rbacManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
        $this->rbacManager = $rbacManager;
    }

    /**
     * @throws Exception
     */
    public function login($email, $password, $rememberMe)
    {
        // Перевіряємо, увійшов користувач в систему. Якщо так, не дозволяємо
        // йому увійти двічі.
        if ($this->authService->getIdentity() != null) {
            throw new Exception('Already logged in');
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

    /**
     * @throws Exception
     */
    public function logout()
    {
        if ($this->authService->getIdentity() == null) {
            throw new Exception('The user is not logged in');
        }

        $this->authService->clearIdentity();
    }

    /**
     * Це простий фільтр контролю доступу. Він може обмежувати доступ до певних сторінок
     * для неавторизованих користувачів.
     *
     * Даний метод використовує ключ у файлі конфігурації та визначає, чи дозволено
     * поточному відвідувачеві доступ до заданої дії контролера чи ні. Якщо дозволено,
     * він повертає true, інакше – false.
     * @throws Exception
     */
    public function filterAccess($controllerName, $actionName): int
    {
// Визначаємо режим - 'обмежувальний' (за умовчанням) або 'дозвільний'. В обмежувальному
        // в режимі всі дії контролера повинні бути явно перераховані під ключом конфігурації 'access_filter',
        // та доступ до будь-якої не зазначеної дії для неавторизованих користувачів заборонено.
        // У дозвільному режимі, якщо дія не вказана під ключом 'access_filter', доступ до неї
        // Дозволений для всіх (навіть для незалогінених користувачів).
        // Обмежувальний режим є безпечнішим, і рекомендується використовувати його.
        $mode = $this->config['options']['mode'] ?? 'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new Exception('Invalid access filter mode (expected either restrictive or permissive mode');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*')
                        //Усі можуть переглядати цю сторінку.
                        return self::ACCESS_GRANTED;
                    else if (!$this->authService->hasIdentity()) {
                        // Тільки автентифіковані користувачі можуть переглядати сторінку.
                        return self::AUTH_REQUIRED;
                    }

                    if ($allow=='@') {
                        // Будь-який автентифікований користувач може переглядати сторінку.
                        return self::ACCESS_GRANTED;
                    } else if (substr($allow, 0, 1)=='@') {
                        // Тільки користувачі з певним привілеєм можуть переглядати сторінку.
                        $identity = substr($allow, 1);
                        if ($this->authService->getIdentity()==$identity)
                            return self::ACCESS_GRANTED;
                        else
                            return self::ACCESS_DENIED;
                    } else if (substr($allow, 0, 1)=='+') {
                        // Тільки користувачі з цим привілеєм можуть переглядати сторінку.
                        $permission = substr($allow, 1);
                        if ($this->rbacManager->isGranted(null, $permission))
                            return self::ACCESS_GRANTED;
                        else
                            return self::ACCESS_DENIED;
                    } else {
                        throw new Exception('Unexpected value for "allow" - expected ' .
                            'either "?", "@", "@identity" or "+permission"');
                    }
                }
            }
        }


        // В обмежувальному режимі ми вимагаємо автентифікації для будь-якої дії, не
        // перерахованого під ключом 'access_filter' та відмовляємо у доступі авторизованим користувачам
        // (З міркувань безпеки).
        if ($mode=='restrictive') {
            if(!$this->authService->hasIdentity())
                return self::AUTH_REQUIRED;
            else
                return self::ACCESS_DENIED;
        }

        // Дозволити доступ до цієї сторінки.
        return self::ACCESS_GRANTED;
    }
}