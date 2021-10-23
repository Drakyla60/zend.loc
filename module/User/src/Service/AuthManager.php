<?php

namespace User\Service;

use Laminas\Authentication\Result;

class AuthManager
{
    private $authService;
    private $sessionManager;
    private $config;

    public function __construct($authService, $sessionManager, $config)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->config = $config;
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

    /**
     * This is a simple access control filter. It is able to restrict unauthorized
     * users to visit certain pages.
     *
     * This method uses the 'access_filter' key in the config file and determines
     * whenther the current visitor is allowed to access the given controller action
     * or not. It returns true if allowed; otherwise false.
     * @throws \Exception
     */
    public function filterAccess($controllerName, $actionName): bool
    {
        // Determine mode - 'restrictive' (default) or 'permissive'. In restrictive
        // mode all controller actions must be explicitly listed under the 'access_filter'
        // config key, and access is denied to any not listed action for unauthorized users.
        // In permissive mode, if an action is not listed under the 'access_filter' key,
        // access to it is permitted to anyone (even for not logged in users.
        // Restrictive mode is more secure and recommended to use.
        $mode = $this->config['options']['mode'] ?? 'restrictive';
        if ($mode!='restrictive' && $mode!='permissive')
            throw new \Exception('Invalid access filter mode (expected either restrictive or permissive mode');

        if (isset($this->config['controllers'][$controllerName])) {
            $items = $this->config['controllers'][$controllerName];
            foreach ($items as $item) {
                $actionList = $item['actions'];
                $allow = $item['allow'];
                if (is_array($actionList) && in_array($actionName, $actionList) ||
                    $actionList=='*') {
                    if ($allow=='*')
                        return true; // Anyone is allowed to see the page.
                    else if ($allow=='@' && $this->authService->hasIdentity()) {
                        return true; // Only authenticated user is allowed to see the page.
                    } else {
                        return false; // Access denied.
                    }
                }
            }
        }

        // In restrictive mode, we forbid access for unauthorized users to any
        // action not listed under 'access_filter' key (for security reasons).
        if ($mode=='restrictive' && !$this->authService->hasIdentity())
            return false;

        // Permit access to this page.
        return true;
    }
}