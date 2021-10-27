<?php

namespace User\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class AccessPlugin extends AbstractPlugin
{
    private $rbacManager;

    public function __construct($rbacManager)
    {
        $this->rbacManager = $rbacManager;
    }

    /**
     * Проверяет наличие заданной привилегии у залогиненного в текущий момент пользователя.
     * @param string $permission Имя привилегии.
     * @param array $params Опциональные параметры (используются только если привилегия связана с утверждением).
     */
    public function __invoke($permission, $params = [])
    {
        return $this->rbacManager->isGranted(null, $permission, $params);
    }
}