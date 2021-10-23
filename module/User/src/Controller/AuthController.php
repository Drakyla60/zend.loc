<?php

namespace User\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Uri\Uri;
use Laminas\View\Model\ViewModel;
use User\Form\LoginForm;

class AuthController extends AbstractActionController
{
    private $entityManager;
    private $authManager;
    private $userManager;

    public function __construct($entityManager, $authManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    /**
     * @throws \Exception
     */
    public function loginAction()
    {
        // Витягує URL перенаправлення (якщо такий передається). Ми переспрямуємо користувача
        // на даний URL після успішної аутентифікації.
        $redirectUrl = (string) $this->params()->fromQuery('redirectUrl', '');
        if (strlen($redirectUrl) > 2048) {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        // Перевіряємо, чи є взагалі в базі даних користувачі. Якщо їх немає,
        // створюємо користувача 'Admin'.
        $this->userManager->createAdminUserIfNotExists();

        $form = new LoginForm();
        $form->get('redirect_url')->setValue($redirectUrl);

        // Зберігаємо статус входу на сайт.
        $isLoginError = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {

                $data = $form->getData();
                $result = $this->authManager->login($data['email'], $data['password'], $data['remember_me']);

                if ($result->getCode() == Result::SUCCESS) {
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl)) {
                        // Перевірка нижче потрібна для запобігання можливих атак перенаправлення
                        // (коли хтось намагається перенаправити користувача на інший домен).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost() != null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // Якщо заданий URL перенаправлення, перенаправляємо на нього користувача;
                    // інакше перенаправляємо користувача на сторінку Home.
                    if (empty($redirectUrl)) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                } else {
                    $isLoginError = true;
                }
            } else {
                $isLoginError = true;
            }
        }

        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('home');
    }
}