<?php


namespace Application\Controller;


use Application\Form\ChangeProfileSecurityForm;
use Application\Form\ChangeProfileSettingsForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use User\Entity\User;

class ProfileController extends AbstractActionController
{
    private $authService;
    private $entityManager;
    private $userManager;
    private $imageManager;

    public function __construct(
        $authService,
        $entityManager,
        $userManager,
        $imageManager
    )
    {
        $this->authService = $authService;
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->imageManager = $imageManager;
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
                    $this->logger('err', 'Старий пароль введено невірно. Спробуйте ще раз : '. $data['email']);
                } else {
                    $this->flashMessenger()->addSuccessMessage('Новий пароль збережено успішно.');
                    $this->logger('info', 'Новий пароль збережено успішно : '. $data['email']);
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
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $form->setData($data);
            try {
                if($form->isValid()) {
                    $data = $form->getData();
                    $data = $this->imageManager->uploadUserImage($data);
//                    $data = $this->imageManager->resizeUploadImage($data, 50, 50);
//                    $data = $this->imageManager->resizeUploadImage($data, 150, 150);
                    if (!$this->userManager->changeProfile($user, $data)) {
                        $this->flashMessenger()->addErrorMessage('Налаштування не вдалося зберегти. Спробуйте ще раз');
                        $this->logger('err', 'Налаштування не вдалося зберегти. Спробуйте ще раз : '. $data['email']);
                    } else {
                        $this->flashMessenger()->addSuccessMessage('Налаштування профілю збережено.');
                        $this->logger('info', 'Налаштування профілю збережено. : '. $data['email']);
                    }
                    return $this->redirect()->toRoute('profile_settings');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
                return $this->redirect()->toRoute('profile_settings');
            }

        }
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