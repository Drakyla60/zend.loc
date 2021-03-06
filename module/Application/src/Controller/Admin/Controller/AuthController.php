<?php

namespace Application\Controller\Admin\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Uri\Uri;
use Laminas\View\Model\ViewModel;
use Application\Form\Admin\LoginForm;
use Application\Form\Admin\RegistrationUserForm;

class AuthController extends AbstractActionController
{
    private $entityManager;
    private $authManager;
    private $userManager;
    private $authService;
    private $reCaptchaManager;

    private $rememberMe = 1; //для входу після реєстрації

    public function __construct($entityManager, $authManager, $userManager, $reCaptchaManager, $authService)
    {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->userManager = $userManager;
        $this->authService = $authService;
        $this->reCaptchaManager = $reCaptchaManager;
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
        $recaptcha = $this->reCaptchaManager->init();

        $form->get('redirect_url')->setValue($redirectUrl);

        // Зберігаємо статус входу на сайт.
        $isLoginError = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            $result = $this->reCaptchaManager->checkReCaptcha($data['g-recaptcha-response']);

            if ($form->isValid() && true == $result) {

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
        $this->layout()->setTemplate('layout/auth_layout');
        return new ViewModel([
            'form' => $form,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl,
            'recaptcha' => $recaptcha
        ]);
    }

    public function registrationAction()
    {
        $form = new RegistrationUserForm($this->entityManager);
        $recaptcha = $this->reCaptchaManager->init();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            $result = $this->reCaptchaManager->checkReCaptcha($data['g-recaptcha-response']);

            if ($form->isValid() && true == $result) {

                $data = $form->getData();
                $user = $this->userManager->registrationUser($data);

                $result = $this->userManager->createEmailConfirmationToken($user);
                return $this->redirect()->toRoute('home');
            }
        }
        $this->layout()->setTemplate('layout/auth_layout');
        return new ViewModel([
            'form' => $form,
            'recaptcha' => $recaptcha
        ]);
    }

    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('home');
    }

    public function notAuthorizedAction()
    {
        $this->layout()->setTemplate('layout/application_layout');
        $this->getResponse()->setStatusCode(403);

        return new ViewModel();
    }
}