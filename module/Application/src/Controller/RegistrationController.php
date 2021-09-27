<?php

namespace Application\Controller;

use Application\Form\RegistrationForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RegistrationController extends AbstractActionController
{
    /**
     * @var
     */
    private $sessionContainer;

    public function __construct($sessionContainer)
    {
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function indexAction()
    {
        // Визначаємо який зараз крок
        $step = 1;
        if (isset($this->sessionContainer->step)) {
            $step = $this->sessionContainer->step;
        }
        // Перевіряємо щоб крок був дійсним
        if ($step < 1 || $step  > 3) $step = 1;

        // Якщо зараз 1 крок створюєм масив в сесії
        // в який ми будемо записувати дані які ввів користувач
        if (1 == $step) {
            $this->sessionContainer->userChoices = [];
        }

        $form = new RegistrationForm($step);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $this->sessionContainer->userChoices["step$step"] = $data;

                $step++;
                $this->sessionContainer->step = $step;

                if ($step > 3) {
                    return $this->redirect()->toRoute('registration', ['action' => 'review']);
                }

                return $this->redirect()->toRoute('registration');
            }
        }

        $viewModel = new ViewModel([
            'form' => $form
        ]);
        $viewModel->setTemplate("application/registration/step$step");

        return $viewModel;
    }

    public function reviewAction()
    {
        // Валидируем данные сессии.
        if(!isset($this->sessionContainer->step) ||
            $this->sessionContainer->step<=3 ||
            !isset($this->sessionContainer->userChoices)) {
            throw new \Exception('Извините, данные пока не доступны для проверки');
        }

        // Извлекаем из сессии выборы пользователя.
        $userChoices = $this->sessionContainer->userChoices;

        return new ViewModel([
            'userChoices' => $userChoices
        ]);
    }
}