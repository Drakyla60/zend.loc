<?php


namespace Application\Controller;


use Application\Form\ChangeProfileSecurityForm;
use Application\Form\ChangeProfileSettingsForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use User\Entity\User;
use User\Form\PasswordChangeForm;

class ProfileController extends AbstractActionController
{
    private $authService;
    private $entityManager;
    private $userManager;

    public function __construct($authService, $entityManager, $userManager)
    {
        $this->authService = $authService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction(): ViewModel
    {
        $email = $this->authService->getIdentity();

        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['email' => $email]);

        return new ViewModel([
            'user' => $user,
        ]);
    }

    public function settingsAction()
    {
        $email = $this->authService->getIdentity();

        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $changeProfileSecurityForm = new ChangeProfileSecurityForm();
        $changeProfileSettingsForm = new ChangeProfileSettingsForm($this->entityManager, $user);

//        if ($this->getRequest()->isPost()) {
//            $data = $this->params()->fromPost();
//            $form->setData($data);
//
//            if($form->isValid()) {
//                $data = $form->getData();
//
//                if (!$this->userManager->changePassword($user, $data)) {
//                    $this->flashMessenger()->addErrorMessage(
//                        'Старий пароль введено невірно. Спробуйте ще раз');
//                } else {
//                    $this->flashMessenger()->addSuccessMessage('Новий пароль збережено успішно.');
//                }
//
//                return $this->redirect()->toRoute('profile_settings');
//            }
//        }
        return new ViewModel([
            'user' => $user,
            'changeProfileSecurityForm' => $changeProfileSecurityForm,
            'changeProfileSettingsForm' => $changeProfileSettingsForm,
        ]);
    }
    //Настройки Безпеки
    public function securityAction()
    {
        $email = $this->authService->getIdentity();

        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new ChangeProfileSecurityForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()) {
                $data = $form->getData();

                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Старий пароль введено невірно. Спробуйте ще раз');
                } else {
                    $this->flashMessenger()->addSuccessMessage('Новий пароль збережено успішно.');
                }

                return $this->redirect()->toRoute('profile_settings');
            }
        }
        return $this->redirect()->toRoute('profile_settings');
    }
    //Настройки профіля
    public function profileAction()
    {
        $email = $this->authService->getIdentity();

        $user = $this->entityManager
            ->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $form = new ChangeProfileSettingsForm($this->entityManager, $user);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
//            @TODO При валідації не приходить аватарка треба завтра подивитися
            try {
                if($form->isValid()) {
                    $data = $form->getData();

                    if (!$this->userManager->changeProfile($user, $data)) {
                        $this->flashMessenger()->addErrorMessage(
                            'Налаштування не вдалося зберегти. Спробуйте ще раз');
                    } else {
                        $this->flashMessenger()->addSuccessMessage('Налаштування профілю збережено.');
                    }
                    return $this->redirect()->toRoute('profile_settings');

                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
//                echo $e->getMessage();
//                die();
                return $this->redirect()->toRoute('profile_settings');
            }

        }
        $this->flashMessenger()->addSuccessMessage("ffff");
        return $this->redirect()->toRoute('profile_settings');
    }

    public function getJsonAction()
    {
        return new JsonModel([
            'status' => 'SUCCESS',
            'message'=>'Here is your data',
            'data' => [
                'full_name' => 'John Doe',
                'address' => '51 Middle st.'
            ]
        ]);
    }


}